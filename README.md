## Notes

- To keep the wallet IDs isolated from users, I have added wallet code which is generated on wallet creation.
- balance_details table created to sum up all of wallet transactions and use it to calculate total balance, although this would have additional cost while writing to database, I believe it will improve the reading proccess significantly as well as making the database structure more obvious.
- I assumed a user may have more than one wallet and worked by that approach.
- I added user_id field for WalletOperations and Transactions to log the user who did the operation, but because no authentication was required, I used the source wallet user as operation user.

## API Documentation

The postman collection file is located at: storage\api_docs\WalletManagementAPI.postman_collection.json
