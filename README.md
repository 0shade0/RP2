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

accountController (**u izradi**)
* login
* register
* activate

## Modeli
ChorezService --- za dohvat iz baze podataka
* getUserByID(str)
* getUserByUsername(str)
* getUserByEmail(str)
* getUserPasswordByUsername(str)
* addNewUser(User)
* set_registered(int) - postavlja vrijednost registered u bazi

User (isto kao u bazi)
Household (isto kao u bazi)

## Baza podataka

pr_users
* ID
* ID_household
* username
* password (hashirani)
* email
* points
* admin (da/ne)
* registration_sequence
* registered (da/ne)

pr_households
* ID
* name

pr_chores
* ID
* ID_user
* ID_category
* description
* time_next (vrijeme kad se zadatak **idući put** treba prikazati)
* mandatory (da/ne)
* type (jednom(0), dnevni(1), tjedni(2), mjesečni(3), godišnji(4))
* points (vrijednost)

pr_categories
* ID
* ID_household (kućanstvo kojem kategorije pripadaju)
* name

pr_rewards
* ID
* ID_user (kojem korisniku se prikazuju)
* description
* points_price
