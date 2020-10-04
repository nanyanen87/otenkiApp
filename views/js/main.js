'use strict'

let app = new Vue ({
  el : "#weatherReportApp",
  data:{
    prefecture:"",
    memo:"更新sita",
    weatherReports: [],
    userInput:"text",
    regionName:"都道府県、地域名",
  },
  methods:{
    getReq: function(){
      //todo async awaitで書き直す
      axios.get("/routes/mainController.php",{
        params: {
          prefecture: this.prefecture,
        }
      }).
      then(res=>{
        console.log("success");
        console.log(res.data);

        //爆裂にダサいけど応急処置
        if(typeof(res.data)==="object"){
          this.weatherReports = res.data;
          this.regionName = res.data[0].prefecture;
        }else{
          alert(res.data);
        }
        // ↓これいらない？おそらくブラウザが勝手にjson読み取ってる
        // let dataEncoded = JSON.parse(res.data);
        // console.log(dataEncoded);
      }).
      catch(error=>{
        //自分で作ったerrorをここにルーティングするには、レスポンスのヘッダを500に形成しないとダメくさい
        alert(error);
        console.log(error);
        console.log("errorだyo");
      })
    },
    postReq: function(){
      //ブラウザでポストする用のクラス
      let params = new URLSearchParams();
      params.append('memo', this.memo);
      axios.post("/routes/controller.php",params).
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