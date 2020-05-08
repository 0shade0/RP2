# RP2
## Controlleri
choreController
* popis mojih zadataka - index
* detalj nekog zadatka - show&id=...
* stvori novi zadatak - create

userController
* pregled mojih podataka - index
* pregled podataka ukućana - show&id=...
* pregled kućanstva - household
* moji zadaci - chores
* nagrade - rewards
* login, register // u izradi

Moguće ubaciti loginController?

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
