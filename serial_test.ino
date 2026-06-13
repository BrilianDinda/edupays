void setup() {
  Serial.begin(115200);
  delay(1000);
  Serial.println("Serial TEST - ESP32 ready");
}

void loop() {
  Serial.println("Time: " + String(millis()));
  delay(1000);
}