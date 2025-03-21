# Workflow

Il seguente documento include il ragionamento eseguito per ottenere la soluzione alla problematica.

Assunto che:
- il carattere 'p' sia relativo ai pound
- il carattere 's' sia relativo agli shilling
- il carattere 'd' sia relativo ai pence
  

- Un 'p' equivale a 20 's', e v.v.
- Un 's' equivale a 12 'd', e v.v.  


- 's' sarà sempre compreso fra 0 e 19,
- 'd' sarà sempre compreso fra 0 e 11,


## Algoritmo risolutivo per le varie operazioni

- Trasformare i P e gli S in D.
- Effettuare l'operazione matematica
- Riconvertire il risultato in P, S, D 


# Endpoints

### GET /sum 

- Examples
  - /sum?firstValue=5p17s8d&secondValue=3p4s10d

### GET /subtraction
### GET /multiplication

  - Parametri query
    - 1° valore 
    - 2° valore 
  Formato parametri: stringa (XpYsZd)  

  - Risposta
    - stringa nel seguente formato: XpYsZd

### GET /division
    - Risposta
    - stringa nel seguente formato: XpYsZd (in base al caso, potrà esser inserito il seguente valore: (XpYsZd)

