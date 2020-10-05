'use strict'

const app = new Vue ({
  el : "#weatherReportApp",
  data:{
    prefecture:"大阪府",
    memo:[],
    weatherReports: [],
    userInput:"text",
    regionName:"都道府県、地域名",
    toggles:{},
  },
  methods:{
    showMemo: function(i){
      this.toggles[i]===false ? this.toggles[i]=true:this.toggles[i]=false;
      console.log(this.toggles);
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
        // console.log(res.data);
          this.weatherReports = res.data;
          this.regionName = res.data[0].prefecture;
          let memo=[];
          let toggles={};
          res.data.forEach(function(e){
            let key = e.id;
            toggles[key] = false;
          });
          this.toggles = toggles;
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
    postReq: function(i){
      //ブラウザでポストする用のクラス
      let params = new URLSearchParams();
      //何番目のメモか、パラメータ追加しないと。
      params.append('memoId', i);
      params.append('memoText', this.memo[i]);
      params.append('prefecture',this.regionName)
      
      axios.post("/routes/mainController.php",params).
      then(res=>{
        console.log("success");
        console.log(res);
        this.weatherReports = res.data;
      }).
      catch(error=>{
        console.log(error);
        console.log("errorだよ");
      })
    }
  }
});