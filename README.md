Project Setup Commands
git clone https://github.com/deepak-2707/zestbrain-assignment
cd ../zestbrain-assignment
composer install
npm install 
#change your env database details
php artisan migrate #for table creation
#to run the project, run both the below command with 2 different terminal
php artisan serve 
npm run dev
