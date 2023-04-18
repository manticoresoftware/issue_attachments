echo "--- facet multi-query"
time mysql -P7406 -h 127.0.0.1 -e "select * from test limit 0 FACET data FACET data2 FACET data3 FACET data4 FACET data5 FACET data6 FACET data7;" 
echo " "

echo "--- facet many queries"
time mysql -P7406 -h 127.0.0.1 -e "select * from test limit 0; select * from test limit 0 FACET data;select * from test limit 0 FACET data2;select * from test limit 0 FACET data3;select * from test limit 0 FACET data4;select * from test limit 0 FACET data5;select * from test limit 0 FACET data6;select * from test limit 0 FACET data7; show meta;" 
echo " "

echo "--- group by many queries"
time mysql -P7406 -h 127.0.0.1 -e "select * from test limit 0; select data, count(*) from test group by data; select data2, count(*) from test group by data2;select data3, count(*) from test group by data3;select data4, count(*) from test group by data4;select data5, count(*) from test group by data5;select data6, count(*) from test group by data6;select data7, count(*) from test group by data7;" 
echo " "

echo "--- facet multi-query threads=0"
time mysql -P7406 -h 127.0.0.1 -e "select * from test limit 0 option threads=1 FACET data FACET data2 FACET data3 FACET data4 FACET data5 FACET data6 FACET data7;" 
echo " "