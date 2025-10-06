#!/bin/bash

# Run Database Fix Script
# This will update the Azure SQL database with proper email data

echo "🔧 Fixing Database Data..."

# Connection details for Azure SQL
SERVER="uniportal-sql-server.database.windows.net"
DATABASE="uniportal-db"
USERNAME="sqladmin"
PASSWORD="Admin123"

echo "📊 Updating student emails..."
sqlcmd -S $SERVER -d $DATABASE -U $USERNAME -P $PASSWORD -i fix_database_data.sql

echo "✅ Database fix completed!"
echo "🎯 All emails should now be properly connected in the database"
