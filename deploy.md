
// deploy to prod
php vendor/phing/phing/bin/phing -f phing_build_prod.xml  

// to build and include vendors
php vendor/phing/phing/bin/phing -f phing_build_prod.xml  -D include_vendors=1

