#include <WiFi.h>
#include <HTTPClient.h>
#include <DHT11.h> 
#define SIGNAL_PIN 2
String URL = "http://192.168.0.17/meteo/script.php";
DHT11 dht11(15);

volatile unsigned long impulseCount = 0; // Numărul de impulsuri
unsigned long lastTime = 0; // Timpul ultimului impuls
float windSpeed = 0; // Viteza vântului
const char* ssid = "UPC8060876"; 
const char* password = "r4pZdmtjczvu"; 
int sensorValue;

void setup() {
  Serial.begin(9600);

  // Inițializarea pinului de semnal ca pin de intrare
  pinMode(SIGNAL_PIN, INPUT);
  
  // Atașarea funcției de întrerupere la pinul de semnal pentru a număra impulsurile
  attachInterrupt(digitalPinToInterrupt(SIGNAL_PIN), countImpulse, RISING); 
connectWiFi();
}

void loop() {

  // read the input on analog pin 4:
 sensorValue = analogRead(34);
  int direction = map(sensorValue, 0, 1023, 0, 360);
  Serial.println(sensorValue);

 
  // Citirea valorilor de la senzori
  int temperature = 0;
  int humidity = 0;
  float windSpeed = 0;
  int windDirection = 0;

  // Attempt to read the temperature and humidity values from the DHT11 sensor.
  int result = dht11.readTemperatureHumidity(temperature, humidity);

  // Check the results of the readings.
  // If the reading is successful, print the temperature and humidity values.
  // If there are errors, print the appropriate error messages.
  if (result == 0) {
    Serial.print("Temperature: ");
    Serial.print(temperature);
    Serial.print(" °C\tHumidity: ");
    Serial.print(humidity);
    Serial.println(" %");
  } else {
    // Print error message based on the error code.
    Serial.println(DHT11::getErrorString(result));
  }

  // Calcularea vitezei vântului la fiecare secundă
  if (millis() - lastTime >= 1000) {
    // Calcularea viteza vântului în metri pe secundă
    windSpeed = impulseCount / 2.4; // Factorul de scalare depinde de specificațiile senzorului
    
    // Afișarea vitezei vântului pe portul serial
    Serial.print("Viteza vantului: ");
    Serial.print(windSpeed);
    Serial.println(" m/s");
    
    // Resetarea numărătorului de impulsuri și actualizarea timpului ultimului impuls
    impulseCount = 0;
    lastTime = millis();
  }

  
  // print out the value you read:
  Serial.print("ADC : ");
  Serial.println(sensorValue);
  Serial.print("Direction : ");
  Serial.println(direction / 4);

  if ((direction / 4 >= 0 && direction / 4 < 23) || (direction / 4 >= 338 && direction / 4 <= 360)) {
    Serial.println("North");
  } else if (direction / 4 >= 23 && direction / 4 < 68) {
    Serial.println("North-East");
  } else if (direction / 4 >= 68 && direction / 4 < 113) {
    Serial.println("East");
  } else if (direction / 4 >= 113 && direction / 4 < 158) {
    Serial.println("South-East");
  } else if (direction / 4 >= 158 && direction / 4 < 203) {
    Serial.println("South");
  } else if (direction / 4 >= 203 && direction / 4 < 248) {
    Serial.println("South-West");
  } else if (direction / 4 >= 248 && direction / 4 < 293) {
    Serial.println("West");
  } else if (direction / 4 >= 293 && direction / 4 < 338) {
    Serial.println("North-West");
  }

  delay(300);  // delay in between reads for stability

  String postData = "Temperatura=" + String(temperature) + "&Umiditate=" + String(humidity) + "&Viteza=" + String(windSpeed) + "&Directie=" + String(direction/4);
  
  HTTPClient http;
  http.begin(URL);
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");
  
  int httpCode = http.POST(postData);
  String payload = "";

  if(httpCode > 0) {
    // file found at server
    if(httpCode == HTTP_CODE_OK) {
      String payload = http.getString();
      Serial.println(payload);
    } else {
      // HTTP header has been send and Server response header has been handled
      Serial.printf("[HTTP] GET... code: %d\n", httpCode);
    }
  } else {
    Serial.printf("[HTTP] GET... failed, error: %s\n", http.errorToString(httpCode).c_str());
  }
  
  http.end();  //Close connection

  Serial.print("URL : "); Serial.println(URL); 
  Serial.print("Data: "); Serial.println(postData);
  Serial.print("httpCode: "); Serial.println(httpCode);
  Serial.print("payload : "); Serial.println(payload);
  Serial.println("--------------------------------------------------");
  delay(5000);
}


void connectWiFi() {
  WiFi.mode(WIFI_OFF);
  delay(1000);
  //This line hides the viewing of ESP as wifi hotspot
  WiFi.mode(WIFI_STA);
  
  WiFi.begin(ssid, password);
  Serial.println("Connecting to WiFi");
  
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
    
  Serial.print("connected to : "); Serial.println(ssid);
  Serial.print("IP address: "); Serial.println(WiFi.localIP());
}
void countImpulse() {
  impulseCount++;
}