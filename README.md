# Chorez
* [Controlleri](#Controlleri)
* [Modeli](#Modeli)
* [Baza podataka](#Baza)

## Controlleri <a name="Controlleri"></a>
choreController
* popis mojih zadataka - index
* detalj nekog zadatka - show&id=...
* stvori novi zadatak - create

userController
* pregled mojih podataka - index
* pregled podataka ukućana - show&id=...
* pregled kućanstva - household
* nagrade - rewards&id=... (za admine)

accountController
* login
* register
* activate

## Modeli <a name="Modeli"></a>
**ChorezService** --- za dohvat iz baze podataka
* getUserByID(str)
* getUserByUsername(str)
* getUserByEmail(str)
* addNewUser(User) - vraća ID dodanog korisnika
* addUserToHousehold(User, Household)
* set_registered(int) - postavlja vrijednost registered u bazi

* addNewHousehold(Household) - vraća ID dodanog kućanstva
* getHouseholdByID
* setCompleted(Chore) - stavlja jednokratni zadatak u stanje "done", a ako se ponavlja stavlja ga na iduci period.
* getAllCategories([Household]) - vraća array defaultnih kategorija, i kategorija koje koristi neko kućanstvo ako je kućanstvo prosljeđeno funkciji.
* addNewCategory(Household, str) - vraća ID dodane kategorije

* getRewardsByID(userID) 
* getRewardByID(userID, rewardID)
* addNewReward(Reward) - vraća ID dodane nagrade
* deleteRewardByID(str)
* buyReward(userID, rewardID, points, price)

**User** (isto kao u bazi)

**Household** (isto kao u bazi)

**Chore** (isto kao u bazi)

**Category** (isto kao u bazi)
* getDefaultCategories - vraća defaultne kategorije

Reward (isto kao u bazi)

## Baza podataka <a name="Baza"></a>

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
* done (da/ne)

pr_categories
* ID
* ID_household (kućanstvo kojem kategorije pripadaju)
* name

pr_rewards
* ID
* ID_user (kojem korisniku se prikazuju)
* description
* points_price
* purchased (da/ne)
