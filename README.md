# Commission calculator

Commission calculator that calculates commission fees from provided csv file by the following rules


### Deposit rule
All deposits are charged 0.03% of deposit amount.

### Withdraw rules
There are different calculation rules for `withdraw` of `private` and `business` clients.

**Private Clients**
- Commission fee - 0.3% from withdrawn amount.
- 1000.00 EUR for a week (from Monday to Sunday) is free of charge. Only for the first 3 withdraw operations per a week. 4th and the following operations are calculated by using the rule above (0.3%). If total free of charge amount is exceeded them commission is calculated only for the exceeded amount (i.e. up to 1000.00 EUR no commission fee is applied).


**Business Clients**
- Commission fee - 0.5% from withdrawn amount.

## Input data
Operations are given in a CSV file. In each line of the file the following data is provided:
1. operation date in format `Y-m-d`
2. user's identificator, number
3. user's type, one of `private` or `business`
4. operation type, one of `deposit` or `withdraw`
5. operation amount (for example `2.12` or `3`)
6. operation currency, three letter iso code e.g. `EUR`, `USD`, `JPY`

## Usage
`php script.php input.csv` where `input.csv` is the input file

## Testing
`composer run test`
