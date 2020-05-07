# RP2
## Baza podataka

kucanstvo
* ID
* ime

korisnik
* ID
* ID_kucanstvo
* username
* password
* email
* admin (da ili ne)

zadatak
* ID
* ID_korisnik
* opis
* kategorija
* obavezno (da ili ne)
* vrsta (tjedni, dnevni, mjesečni...)
* vrijednost (u bodovima)

nagrada
* ID
* opis
* cijena

## Klase:
class kucanstvo
  * ID, ime
  * korisnici[]
  * grupni_zadaci[]
  * grupne_nagrade[]

class korisnik
  * email
  * kucanstvoID
  * ime_kucanstva
  * bodovi
  * pojedinacni_zadaci[]
  * pojedinacne_nagrade[]
  * rijesi_zadatak()
  * uzmi_nagradu()
  
nasljednici od korisnik:
  * class admin
      * dodaj_korisnika()
      *  zadaj_zadatak()
      *  dodaj_nagradu()
  * class regular

class zadatak
  * ime
  * opis
  * kategorija
  * bool mogu/moraju
  * vrsta // dnevni, tjedni, samo jednom
  * zaduzene_osobe[]
  * broj_bodova
  
class nagrada
  * opis
  * cijena
  
