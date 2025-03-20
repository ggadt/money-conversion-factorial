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


## Algoritmo risolutivo per la somma

Assunto che:
- avrò due addendi, composti da 3 parti:
  - p
  - s
  - d

1. Sommo la parte d dei due addendi (d' e d'')

3. calcolo il modulo fra il risultato della somma, e 12
4. Scrivo il risultato in D
4. calcolo il quoziente senza resto, fra la somma e 12: q'
   5. Il quoziente diventerà un riporto da aggiungere alla somma successiva degli S
4. scrivo il nuovo D, dovuto al modulo 12 della somma.

1. Sommo la parte s dei due addendi (s' e s'', aggiungento il resto precedente (q'))
1. calcolo il modulo 20 della somma fra s' e s'' e q'.
2. scrivo il risultato in S
2. calcolo il quoziente fra S e 20 = q''

1. sommo p', p'' e q'', scrivo in P
