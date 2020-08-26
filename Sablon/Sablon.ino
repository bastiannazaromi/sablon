#include <Arduino.h> // Library arduino

// Sensor DHT
#include <DHT.h>

#define DHTPIN D9
#define DHTTYPE DHT11

DHT dht(DHTPIN, DHTTYPE);

int before_suhu = 25;

// Library Wifi
#include <ESP8266WiFi.h>
#include <ESP8266WiFiMulti.h>
#include <ESP8266HTTPClient.h>

#define USE_SERIAL Serial
ESP8266WiFiMulti WiFiMulti;
HTTPClient http;

// relay
#define relay D5
#define relay_on LOW
#define relay_off HIGH

// button
#define button_1 D3
#define button_2 D4

// Alamat pengiriman data
String simpan = "http://duniaiot.com/sablon/Data/save?suhu=";
String addPemakaian = "http://duniaiot.com/sablon/Data/addPemakaian?delay=";
String updateKontrol = "http://duniaiot.com/sablon/Data/updateKontrol?keterangan=";

String respon;
String kontrol, delay_alat;

void setup() {
  Serial.begin(115200);
  USE_SERIAL.begin(115200);
  USE_SERIAL.setDebugOutput(false);
  
  for(uint8_t t = 3; t > 0; t--) {
      USE_SERIAL.printf("[SETUP] Tunggu %d...\n", t);
      USE_SERIAL.flush();
      delay(1000);
  }

  WiFi.mode(WIFI_STA);
  WiFiMulti.addAP("Project", "12345678");

  for (int u = 1; u <= 5; u++)
  {
    if ((WiFiMulti.run() == WL_CONNECTED))
    {
      USE_SERIAL.println("ye.. wifi konek :)");
      USE_SERIAL.flush();
      delay(1000);
    }
    else
    {
      Serial.println("Wuuu.. wifi belum konek :(");
      delay(1000);
    }
  }

  Serial.print("IP address : ");
  Serial.println(WiFi.localIP());

  pinMode(relay, OUTPUT);
  pinMode(button_1, INPUT_PULLUP);
  pinMode(button_2, INPUT_PULLUP);
  
  digitalWrite(relay, relay_off);

  Serial.print("Persiapan Dimulai\n\n");

  dht.begin();
  
  delay(1000);

}

void loop() {

  // baca sensor suhu
  int suhu = dht.readTemperature();
  if (suhu > 50)
  {
    suhu = before_suhu;  
  }
  else
  {
    before_suhu = suhu;
  }

  Serial.print("Suhu : ");
  Serial.println(suhu);

  Serial.println();

  // jika button 1 di tekan
  if (digitalRead(button_1) == LOW)
  {
    Serial.println("Alat berjalan, delay 7 detik");

    add_pemakaian(7000);
    
    digitalWrite(relay, relay_on);
    delay(7000);
    digitalWrite(relay, relay_off);
  }
  else if (digitalRead(button_2) == LOW) // jika button 2 ditekan
  {
    Serial.println("Alat berjalan, delay 10 detik");

    add_pemakaian(10000);
    
    digitalWrite(relay, relay_on);
    delay(10000);
    digitalWrite(relay, relay_off);
  }
  else
  {
    Serial.println("Alat sedang istirahat");
  }

  // pengiriman data suhu ke database
  if ((WiFiMulti.run() == WL_CONNECTED))
  {
    http.begin( simpan + (String) suhu );
    
    USE_SERIAL.print("[HTTP] Menyimpan data suhu ke database ...\n");
    int httpCode = http.GET();

    if(httpCode > 0)
    {
      USE_SERIAL.printf("[HTTP] kode response GET : %d\n", httpCode);

      if (httpCode == HTTP_CODE_OK)
      {
        respon = http.getString();
        USE_SERIAL.println("Respon : " + respon);
        
        kontrol = respon.substring(0,1);
        USE_SERIAL.println("Kontrol : " + kontrol);

        if (kontrol.toInt() == 1)
        {
          if (respon.length() == 8)
          {
            delay_alat = respon.substring(1,9);
          }
          else if (respon.length() == 7)
          {
            delay_alat = respon.substring(1,8);
          }
          else if (respon.length() == 6)
          {
            delay_alat = respon.substring(1,7);
          }
          else
          {
            delay_alat = respon.substring(1,6);
          }
  
          USE_SERIAL.println("Delay alat : " + delay_alat);
        }
        
        delay(200);
      }
    }
    else
    {
      USE_SERIAL.printf("[HTTP] GET data gagal, error: %s\n", http.errorToString(httpCode).c_str());
    }
    http.end();
  }

  if (kontrol.toInt() == 1) // jika ada kontrol dari web
  {
    Serial.print("Alat berjalan, delay ");
    Serial.print(delay_alat.toInt()/1000);
    Serial.println(" detik");
    digitalWrite(relay, relay_on);
    delay(delay_alat.toInt());
    digitalWrite(relay, relay_off);
    
    update_kontrol();
  }

  delay(1000);

}

void update_kontrol()
{
  if ((WiFiMulti.run() == WL_CONNECTED))
  {
    http.begin( updateKontrol + "Selesai" );
    
    USE_SERIAL.print("[HTTP] Update kontrol ke database ...\n");
    int httpCode = http.GET();

    if(httpCode > 0)
    {
      USE_SERIAL.printf("[HTTP] kode response GET : %d\n", httpCode);

      if (httpCode == HTTP_CODE_OK)
      {
        respon = http.getString();
        USE_SERIAL.println("Respon : " + respon);
        
        delay(200);
      }
    }
    else
    {
      USE_SERIAL.printf("[HTTP] GET data gagal, error: %s\n", http.errorToString(httpCode).c_str());
    }
    http.end();
  }
}

void add_pemakaian(int delay_pemakaian)
{
  if ((WiFiMulti.run() == WL_CONNECTED))
  {
    http.begin( addPemakaian + (String) delay_pemakaian );
    
    USE_SERIAL.print("[HTTP] Tambah pemakaian ke database ...\n");
    int httpCode = http.GET();

    if(httpCode > 0)
    {
      USE_SERIAL.printf("[HTTP] kode response GET : %d\n", httpCode);

      if (httpCode == HTTP_CODE_OK)
      {
        respon = http.getString();
        USE_SERIAL.println("Respon : " + respon);
        
        delay(200);
      }
    }
    else
    {
      USE_SERIAL.printf("[HTTP] GET data gagal, error: %s\n", http.errorToString(httpCode).c_str());
    }
    http.end();
  }
}
