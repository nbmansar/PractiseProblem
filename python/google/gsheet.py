import gspread
from oauth2client.service_account import ServiceAccountCredentials

# Define the scope of the API access
scope = ['https://spreadsheets.google.com/feeds', 'https://www.googleapis.com/auth/drive']

# Credentials of the service account
credentials = ServiceAccountCredentials.from_json_keyfile_name('credentials.json', scope)

# Authorize the client using the credentials
client = gspread.authorize(credentials)

# Open the spreadsheet by its title
spreadsheet = client.open('Ansar_project_title')

# Select a specific worksheet by its index
worksheet = spreadsheet.get_worksheet(0)

# Get all values from the worksheet
values = worksheet.get_all_values()

# Print the retrieved values
for row in values:
    print(row)

