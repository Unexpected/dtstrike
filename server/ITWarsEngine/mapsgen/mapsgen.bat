@echo OFF

mkdir maps\ 2>NUL
mkdir maps\2\ 2>NUL
mkdir maps\3\ 2>NUL
mkdir maps\4\ 2>NUL
mkdir maps\5\ 2>NUL
mkdir maps\6\ 2>NUL

REM Cartes à 2 joueurs
for /l %%x in (1, 1, 33) do (
	java -jar mapgen.jar -nbPlayers 2 -gamerMilitary 1 -gamerEconomic 2 -neutralMilitary 2 -neutralEconomic 5 > maps\2\map%%x.txt
)

REM Cartes à 3 joueurs
for /l %%x in (33, 1, 66) do (
	java -jar mapgen.jar -nbPlayers 3 -gamerMilitary 1 -gamerEconomic 2 -neutralMilitary 2 -neutralEconomic 5 > maps\3\map%%x.txt
)

REM Cartes à 4 joueurs
for /l %%x in (66, 1, 99) do (
	java -jar mapgen.jar -nbPlayers 4 -gamerMilitary 1 -gamerEconomic 2 -neutralMilitary 2 -neutralEconomic 5 > maps\4\map%%x.txt
)

REM Cartes à 4 joueurs
for /l %%x in (100, 1, 132) do (
	java -jar mapgen.jar -nbPlayers 5 -gamerMilitary 1 -gamerEconomic 2 -neutralMilitary 2 -neutralEconomic 5 > maps\5\map%%x.txt
)

REM Cartes à 4 joueurs
for /l %%x in (133, 1, 165) do (
	java -jar mapgen.jar -nbPlayers 6 -gamerMilitary 1 -gamerEconomic 2 -neutralMilitary 2 -neutralEconomic 5 > maps\6\map%%x.txt
)
