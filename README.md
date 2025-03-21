# Money Conversion Assignment

> Fino al 1970 nel Regno Unito il sistema monetario prevedeva **pence**, **shilling** e **pound**.  
> Un pound valeva 20 shilling, ed uno shilling valeva 12 pence. (Un pound valeva 240 pence).

## Assignment
Creare un'applicazione tramite l'ausilio del framework Symfony all'interno di un container che esponga API REST, che permettano di:
1. **Eseguire le principali operazioni aritmetiche**  
   - Somma (5p 17s 8d + 3p 4s 10d = 9p 2s 6d)  
   - Sottrazione (5p 17s 8d - 3p 4s 10d = 2p 12s 10d)  
   - Moltiplicazione con un intero (no decimali) (5p 17s 8d * 2 = 11p 15s 4d)  
   - Divisione resto (tra parentesi) (18p 16s 1d / 15 = 1p 5s 0d) (1s 1d)  
2. Inserire, modificare e rimuovere articoli da un catalogo (un articolo ha un codice id, nome e costo) - Optional
3. Ottenere la lista degli articoli del catalogo o di un singolo articolo (dato ID) - optional

N.B.: Gli endpoint devono poter ricevere e produrre i valori monetari come stringa, usando lo stesso formato degli esempi (Xp, Ys, Zd).
Nel caso della divisione, se è presente un resto, questo sarà indicato tra parentesi usando stesso formato.

## Requirements
- Utilizzo framework symfony
- Esposizione chiamate REST
- Utilizzo containerizzazione con Docker
- Implementaz. obbl. punto 1
- Rilascio come progetto open source su Github / gitlab

## Nice to have

- Implem. p. 2 - 3
- Test
- Swagger / Interfaccia grafica
