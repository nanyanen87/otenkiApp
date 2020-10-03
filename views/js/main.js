'use strict'

let app = new Vue ({
  el : "#weatherReportApp",
  data:{
    prefecture:"",
    memo:"更新",
    weatherReports: [],
  },
  methods:{
    getReq: function(){
      axios.get("/routes/mainController.php",{
        params: {
          prefecture: this.prefecture,
        }
      }).
      then(res=>{
        console.log("success");
        console.log(res.data);
        // this.prefecture = res.data[0].prefecture;
        this.weatherReports = res.data;
        // ↓これいらない？おそらくブラウザが勝手にjson読み取ってる
        // let dataEncoded = JSON.parse(res.data);
        // console.log(dataEncoded);
      }).
      catch(error=>{
        console.log(error);
        console.log("errorだよ");
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