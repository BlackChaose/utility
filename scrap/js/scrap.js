const rp = require('request-promise');
const cheerio = require('cheerio');

let year = [1998,1999,2000,2001,2002,2003,2004,2005,2006,2007,2008,2009,2010,2011,2012,2013,2014,2015,2016,2017,2018];
//let year = [1998,1999,2000];
let month = [1,2,3,4,5,6,7,8,9,10,11,12];
let dataT=[];	
let parse = function(yr, mnt){return new Promise((resolve, reject)=>{

let uri = `https://www.gismeteo.ru/diary/4368/${yr}/${mnt}/`;
let options = {
	uri: uri,
	transform: function(body){
		return cheerio.load(body);
	}
};
console.log(uri);
rp(options)
	.then(($) => {
		let arr = $('table > tbody td[class="first"]').next();
		let idx = 0 ;
		let recordT = '';
		for(idx = 0; idx < arr.length; idx+=1){
			recordT += ` ${arr[idx].children[0].data}`;
			
		}
	
		recordT += '\n';
		let result = {};
		console.log(yr);
		result={"year":yr, "month":mnt, "record":recordT};
		resolve(result);
		}) 	
	.catch((err)=>{console.error(err);});

});
}

let arrPromises = [];
year.forEach(yr_el=>
month.forEach(el=>arrPromises.push(parse(yr_el,el)
	.then((val)=>{
		dataT.push(val);
		console.log("pushed!");
		}
	)
	.catch(
	(err)=>{console.error(err);}
	)))
);

Promise.all(arrPromises)
	.then(()=>{
	console.log("then after Promise.All");	
	//console.log(dataT);

	let buf = [];
	year.forEach(y => {
	month.forEach(m =>{
	buf = dataT.sort((a,b)=>{
		return (a.year*100+a.month)-(b.year*100+b.month);
	});
	
	});
	});

	const fs = require('fs');
	buf.forEach(element => {	
		fs.appendFile("out.txt",`(${element.year} - ${element.month} ): ${element.record}`,function(err){if(err){return console.error(err);}
		console.warn("The file was changed!");
			});}
	);

		console.warn("end...");
	})
	

/*
*/
