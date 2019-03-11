import requests

data = {'username': 'PipfejBob', 
		'start_time': '2019-03-11 08:01'}

r = requests.post('http://localhost/WT_php/upload_starttime.php', data)
print (r.json())

data = {'RowID': RowID, 
		'Time_Start': '2019-03-10 08:17:00', 
		'Time_Stop': '2019-03-10 17:19:00', 
		'Time_Minus': '00:45:00', 
		'Time_WorkStop': '2019-03-10 17:18:00', 
		'WorkTime_InSec': '29820', 
		'WorkTime_InHour': '08:17:00'}

r = requests.post('http://localhost/WT_php/upload_stoptime.php', data)
print (r.json())