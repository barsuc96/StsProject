# StsProject
Content-Type:application/json
add account
account/addAccount
{
    "numberAccount": "PL52415412541554"
}



add wallet
wallet/addWallet
{
    "balance": 20.40,
    "name": "sts",
    "accountId": 10
}
get balance
wallet/getBalance
{
    "walletId": 1
}


substract money
wallet/getMoney
{
    "money": 10,
    "walletId": 7
}


add money
wallet/addMoney
{
    "money": 150,
    "walletId": 7
}
