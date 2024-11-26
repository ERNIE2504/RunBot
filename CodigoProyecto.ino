#include <WiFi.h>
#include <HTTPClient.h>
#include <DHT.h>

#define DHTPIN 4
#define DHTTYPE DHT11

DHT dht(DHTPIN, DHTTYPE);

const char* ssid = "Tec-IoT";
const char* password = "spotless.magnetic.bridge";

void setup() {
  Serial.begin(115200);
  dht.begin();

  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.println("Conectando a WiFi...");
  }
  Serial.println("Conectado a WiFi.");
}

void loop() {
  float h = dht.readHumidity();
  float t = dht.readTemperature();

  if (isnan(h) || isnan(t)) {
    Serial.println("Error leyendo el sensor DHT!");
    return;
  }

  Serial.println("Enviando datos:");
  Serial.println("Temperatura: " + String(t));
  Serial.println("Humedad: " + String(h));

  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    String serverPath = "http://10.25.81.43/IOT/sensor_datos.php?temperatura=" + String(t) + "&humedad=" + String(h);
    http.begin(serverPath);

    int httpResponseCode = http.GET();
    if (httpResponseCode > 0) {
      String response = http.getString();
      Serial.println("Respuesta del servidor: " + response);
    } else {
      Serial.println("Error en la solicitud.");
    }
    http.end();
  } else {
    Serial.println("WiFi desconectado.");
  }

  delay(10000); // Enviar datos cada 5 segundos
}
