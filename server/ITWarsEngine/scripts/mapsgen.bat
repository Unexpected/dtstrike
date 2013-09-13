@echo OFF

mkdir maps\ 2>NUL

REM Cartes à 2 joueurs
for /l %%x in (1, 1, 33) do (
	java -jar mapgen.jar -nbPlayers 2 -gamerMilitary 1 -gamerEconomic 1 -neutralMilitary 1 -neutralEconomic 3 > maps\map%%x.txt
)

REM Cartes à 3 joueurs
for /l %%x in (33, 1, 66) do (
	java -jar mapgen.jar -nbPlayers 3 -gamerMilitary 1 -gamerEconomic 1 -neutralMilitary 1 -neutralEconomic 3 > maps\map%%x.txt
)

REM Cartes à 4 joueurs
for /l %%x in (66, 1, 99) do (
	java -jar mapgen.jar -nbPlayers 4 -gamerMilitary 1 -gamerEconomic 1 -neutralMilitary 1 -neutralEconomic 3 > maps\map%%x.txt
)
