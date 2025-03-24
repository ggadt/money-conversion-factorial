# Workflow

Il seguente documento include note di brainstorming, e il ragionamento eseguito per ottenere la soluzione alla problematica.

## Necessità
- Container docker che espone API REST (caricare su dockerhub)
- API REST OldMoneyCalculator 
- Utilizzo framework symfony

### Bassa priorità
- Test
- swagger
- API REST Products


## Assunzioni

Assunto che:
- il carattere 'p' sia relativo ai pound
- il carattere 's' sia relativo agli shilling
- il carattere 'd' sia relativo ai pence

- Un 'p' equivale a 20 's', e v.v.
- Un 's' equivale a 12 'd', e v.v.  

- 's' sarà sempre compreso fra 0 e 19 (negli input e output)
- 'd' sarà sempre compreso fra 0 e 11 (negli input e output)

- i parametri di input e output non conterranno spazi di divisione; es:
  - NO: 5p 2s 4d
  - SI 5p2s4d
- Nelle API REST i parametri devono essere specificati e in un formato valido (vedi seguente)


## Algoritmo risolutivo per le varie operazioni

- Trasformare i P e gli S in D.
- Effettuare l'operazione matematica
- Riconvertire il risultato in P, S, D 

---

# Endpoints

### GET /sum 

- Parametri query
    - 1° valore - addendo 1 (firstValue)
    - 2° valore - addendo 2 (secondValue)
      Formato parametri: stringa (XpYsZd) (vedi validazione #1)
  
- Request example
  - /sum?firstValue=5p17s8d&secondValue=3p4s10d

- Response example
  - `{result: '9p2s6d'}`

### GET /subtraction

- Parametri query
    - 1° valore
    - 2° valore
      Formato parametri: stringa (XpYsZd) (vedi validazione #1)

- Risposta
    - stringa nel seguente formato: XpYsZd


### GET /multiplication

  - Parametri query
    - 1° valore 
    - 2° valore 
  Formato parametri: stringa (XpYsZd)  

  - Risposta
    - stringa nel seguente formato: XpYsZd

### GET /division
- stringa nel seguente formato: XpYsZd 

---

# Swagger

Lo swagger delle API realizzate è raggiungibile al seguente indirizzo:
`localhost:<porta>/api/doc`


---

## Test

- L'operatore somma deve prendere in input due stringhe che rispettino la validazione #1
- L'operatore somma, date due stringhe valide, restituisce in output una stringa nel formato valido

- L'operatore sottrazione deve prendere in input due stringhe che rispettino la validazione #1
- L'operatore sottrazione, date due stringhe valide, restituisce in output una stringa nel formato valido

- L'operatore moltiplicazione deve prendere in input una stringa che rispetti la validazione #1 e un intero (moltiplicatore)
- L'operatore moltiplicazione, date due stringhe valide, restituisce in output una stringa nel formato valido

- L'operatore divisione deve prendere in input una stringa che rispetti la validazione #1 e un intero (divisore)
- L'operatore divisione, date due stringhe valide, restituisce in output due stringhe nel formato valido (quoziente e resto)

### Validazione  
1. Il parametro deve rispettare la seguente espressione regolare:
    - ^[0-9+]p[0-9+]s[0-9+]d$/i (es. **10p5s1d**)
      - il gruppo [0-9+] indica che può essere inserita una qualsiasi cifra intera compresa tra 0 e 9 da 1 a n volte
      - l'espressione regolare può essere inserita anche in case insensitive (es. 'P' o 'p', 'S' o 's' ecc.)
      - La stringa inserita deve iniziare con una cifra e terminare con il carattere 'd', mantenendo la struttura definita su

