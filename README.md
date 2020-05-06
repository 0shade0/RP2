# RP2
## Shema:
### class kucanstvo
  * ID, ime
  * korisnici[]
  * grupni_zadaci[]
  * grupne_nagrade[]

### class korisnik
  * email
  * kucanstvoID
  * ime_kucanstva
  * bodovi
  * pojedinacni_zadaci[]
  * pojedinacne_nagrade[]
  * rijesi_zadatak()
  * uzmi_nagradu()
  
### nasljednici od korisnik:
  * class admin
      * dodaj_korisnika()
      *  zadaj_zadatak()
      *  dodaj_nagradu()
  * class regular

### class zadatak
  * ime
  * opis
  * kategorija
  * bool mogu/moraju
  * vrsta // dnevni, tjedni, samo jednom
  * zaduzene_osobe[]
  * broj_bodova
  
### class nagrada
  * opis
  * cijena
  
