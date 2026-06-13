#include <SPI.h>
#include <MFRC522.h>
#include <WiFi.h>
#include <HTTPClient.h>
#include <Preferences.h>

#define RST_PIN 22
#define SS_PIN 21

MFRC522 mfrc522(SS_PIN, RST_PIN);
Preferences preferences;

String serverUrl = "";
String lastUid = "";
unsigned long lastTap = 0;
const unsigned long debounce = 3000;

void setup() {
  Serial.begin(115200);

  SPI.begin(18, 19, 23, 21);
  mfrc522.PCD_Init();

  loadConfig();
  connectWiFi();

  Serial.println("Ketik SETWIFI:ssid:password untuk ganti WiFi");
  Serial.println("Ketik SETSERVER:192.168.x.x untuk ganti IP server");
  Serial.println("Tempelkan kartu RFID...");
}

void loop() {
  if (Serial.available()) {
    String cmd = Serial.readStringUntil('\n');
    cmd.trim();

    if (cmd.startsWith("SETWIFI:")) {
      int first = cmd.indexOf(':', 8);
      if (first > 0) {
        String ssid = cmd.substring(8, first);
        String pass = cmd.substring(first + 1);
        preferences.begin("wifi", false);
        preferences.putString("ssid", ssid);
        preferences.putString("pass", pass);
        preferences.end();
        Serial.println("WiFi disimpan.");
        delay(1000);
        ESP.restart();
      }
    }
    else if (cmd.startsWith("SETSERVER:")) {
      String ip = cmd.substring(String("SETSERVER:").length());
      ip.trim();
      if (ip.startsWith(":")) {
        ip = ip.substring(1);
        ip.trim();
      }
      preferences.begin("server", false);
      preferences.putString("url", ip);
      preferences.end();
      Serial.println("Server disimpan: " + ip);
      delay(1000);
      ESP.restart();
    }
  }

  if (!mfrc522.PICC_IsNewCardPresent() || !mfrc522.PICC_ReadCardSerial()) {
    return;
  }

  String uid = getUid();
  Serial.println("Card: " + uid);

  if (uid != lastUid || (millis() - lastTap > debounce)) {
    lastUid = uid;
    lastTap = millis();
    saveScan(uid);
    delay(800);
  }

  mfrc522.PICC_HaltA();
}

String getUid() {
  String uid = "";
  for (byte i = 0; i < mfrc522.uid.size; i++) {
    if (mfrc522.uid.uidByte[i] < 0x10) uid += "0";
    uid += String(mfrc522.uid.uidByte[i], HEX);
  }
  uid.toUpperCase();
  return uid;
}

void loadConfig() {
  preferences.begin("server", true);
  String srv = preferences.getString("url", "");
  preferences.end();

  if (!srv.isEmpty()) {
    serverUrl = "http://" + srv;
    Serial.println("Server: " + serverUrl);
  } else {
    Serial.println("Server belum dikonfigurasi.");
  }
}

void connectWiFi() {
  preferences.begin("wifi", true);
  String ssid = preferences.getString("ssid", "");
  String pass = preferences.getString("pass", "");
  preferences.end();

  if (ssid.isEmpty()) {
    Serial.println("WiFi belum dikonfigurasi.");
    return;
  }

  WiFi.begin(ssid.c_str(), pass.c_str());
  Serial.print("Menghubungkan ke " + ssid);

  int attempts = 0;
  while (WiFi.status() != WL_CONNECTED && attempts < 30) {
    delay(500);
    Serial.print(".");
    attempts++;
  }

  if (WiFi.status() == WL_CONNECTED) {
    Serial.println("\nWiFi OK, IP ESP32: " + WiFi.localIP().toString());
  } else {
    Serial.println("\nWiFi gagal.");
  }
}

void saveScan(String uid) {
  if (serverUrl.isEmpty()) {
    Serial.println("Server URL belum dikonfigurasi. Ketik SETSERVER:<IP>");
    return;
  }

  if (WiFi.status() != WL_CONNECTED) {
    Serial.println("WiFi belum terhubung. Mencoba reconnect...");
    connectWiFi();
  }

  if (WiFi.status() != WL_CONNECTED) {
    Serial.println("Gagal kirim UID karena WiFi tidak terhubung.");
    return;
  }

  HTTPClient http;
  String url = serverUrl + ":8000/api/save-scan";

  Serial.println("Kirim ke: " + url);

  http.begin(url);
  http.addHeader("Content-Type", "application/json");

  String json = "{\"uid\":\"" + uid + "\"}";
  int code = http.POST(json);

  if (code > 0) {
    String resp = http.getString();
    Serial.println("Response: " + resp);
  } else {
    Serial.println("POST failed: " + String(code));
  }

  http.end();
}
