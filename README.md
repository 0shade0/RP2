# RP2
## Controlleri
choreController
* pdescription mojih zadataka - index
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

pr_users
* ID
* ID_household
* username
* password
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
* time (vrijeme kada je zadatak prvi put zadan)
* mandatory (da/ne)
* type (jednom(0), dnevni(1), tjedni(2), mjesečni(3), godišnji(4))
* points (vrijednost)

pr_categories
* ID
* ID_household (kojem kategorije pripadaju)
* name

pr_rewards
* ID
* ID_user (kojem korisniku se prikazuju)
* description
* points_price
