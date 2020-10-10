'use strict'
const cheerio = require('cheerio');
const mysql = require('mysql');
const axiosBase = require('axios');
const util = require('util');
const cron = require('node-cron');

const axios = axiosBase.create({
  baseURL: 'https://www.jma.go.jp/jp/week', 
  responseType: 'document'  
});

const connection = mysql.createConnection({
  host: '',
  user: '',
  password: '',
  database: ''
});

connection.connect((err) => {
  if (err) {
    console.log('error connecting: ' + err.stack);
    return;
  }
  console.log('success');
});


const searchPage = async (url,fileName) => {
  let res = await axios.get(url);

  if (res.status !== 200) {
    console.log(`error status:${res.status}`);
    return;
  }

  // jqueryチックに使えるように変換
  let $ = cheerio.load(res.data);
  let tenkiArray=[];
  let hidukeArray=[];
  //スクレイピング
  let todoufuken = $("form>select>option[value='"+fileName+"']").text();
  $('#infotablefont tr:nth-child(4) td').each((i,element)=>{
    tenkiArray[i]=$(element).text().trim();
  })
  $('#infotablefont tr:nth-child(1) th').each((i,element)=>{
    hidukeArray[i]=$(element).text().trim();
  })
  
  //sql命令文をプロミスが返ってくる関数にする
  let pquery = await util.promisify(connection.query).bind(connection);
  let prefectureIdArray = await pquery(
    "select id from weatherReport "+
    "where prefecture="+"\""+todoufuken+"\""
  );
  prefectureIdArray.forEach((item,i)=>{
    console.log(i);
    console.log(item.id);

    dataUpdate(tenkiArray,hidukeArray,item.id,i);
    // dataInsert(todoufuken,tenkiArray,hidukeArray,i);
  })
};

//取得ページ数for関数でいじるのダサない？
//301-357page
for(let j=301;j<357;j++){
  let fileName = j+'.html';
  let url = '/'+fileName;

  searchPage(url,fileName)
}



//1日おきに実行
//main→名前変更する
// cron.schedule('59 59 23 * * *', ()=>{
//   for(let j=301;j<357;j++){
//     let fileName = j+'.html';
//     let url = '/'+fileName;

//     searchPage(url,fileName)
//   }
// });

//node-cronで順番に若い番号からDBに入れていく
// let j = 301
// cron.schedule('* * * * * *', () => {

//     let fileName = j+'.html';
//     let url = '/'+fileName;
//     main(url,fileName)
//     j++;
// });

//更新メソッド
function dataUpdate (tenki,hiduke,id,index){
  let jpUTC=Date.now();
  let convertJST = new Date(jpUTC);
  let updatedTime = convertJST.toLocaleString('ja-JP').slice(0,-3);
  connection.query(
    "update weatherReport set "+
    "date="+"\""+hiduke[index+1]+"\","+
    "weather="+"\""+tenki[index]+"\","+
    "modified="+"\""+updatedTime+"\" "+
    "where id="+id
  );
}
//挿入メソッド
const dataInsert = (prefecture,tenki,hiduke,index)=>{

  connection.query(
    "insert into weatherReport (prefecture,date,weather,created,modified) "+
    "values("+
    "\""+prefecture+"\","+
    "\""+hiduke[index+1]+"\","+
    "\""+tenki[index]+"\","+
    "\""+updatedTime+"\","+
    "\""+updatedTime+"\""+
    ")"
 );
}
  