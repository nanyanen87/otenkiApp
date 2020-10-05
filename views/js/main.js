'use strict'

let app = new Vue ({
  el : "#weatherReportApp",
  data:{
    prefecture:"大阪府",
    memo:"メモメモ",
    weatherReports: [],
    userInput:"text",
    regionName:"都道府県、地域名",
    toggle:false,
  },
  methods:{
    showMemo: function(){
      this.toggle===false ? this.toggle=true:this.toggle=false;
    },
    clear: function(){
      this.prefecture="";
    },
    getReq: function(){
      //todo async awaitで書き直す
      axios.get("/routes/mainController.php",{
        params: {
          prefecture: this.prefecture,
        }
      }).
      then(res=>{
        console.log("success");
        console.log(res);
          this.weatherReports = res.data;
          this.regionName = res.data[0].prefecture;

        // ↓これいらない？おそらくブラウザが勝手にjson読み取ってる
        // let dataEncoded = JSON.parse(res.data);
        // console.log(dataEncoded);
      }).
      catch(error=>{
        console.log(error);
        console.log(error.response);
        alert(error.response.data);
      })
    },
    postReq: function(){
      //ブラウザでポストする用のクラス
      let params = new URLSearchParams();
      params.append('memo', this.memo);
      axios.post("/routes/mainController.php",params).
      then(res=>{
        console.log("success");
        console.log(res);
      }).
      catch(error=>{
        console.log(error);
        console.log("errorだよ");
      })
    }
  }
});