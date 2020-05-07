# RP2
## Baza podataka

pr_kucanstva
* ID
* ime

pr_korisnici
* ID
* ID_kucanstvo
* username
* password_hash
* email
* bodovi
* admin (da ili ne)
* registracijski_niz
* registriran (da ili ne)

pr_zadaci
* ID
* ID_korisnik
* ID_kategorija
* opis
* vrijeme (vrijeme kada je zadatak prvi put zadan)
* obavezno (da ili ne)
* vrsta (jednom(0), dnevni(1), tjedni(2), mjesečni(3))
* vrijednost (u bodovima)

pr_kategorije
* ID
* ID_kucanstvo (kojem kategorije pripadaju)
* ime

pr_nagrade
* ID
* ID_korisnik (kojem korisniku se prikazuju)
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
