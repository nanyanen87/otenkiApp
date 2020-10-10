'use strict'

const app = new Vue({
  el: "#weatherReportApp",
  data: {
    prefecture: "東京都",
    memo: [],
    weatherReports: [],
    userInput: "text",
    regionName: "都道府県、地域名",
    toggles: {},//boolean型の配列
  },
  methods: {
    showMemo: function (i) {
      this.toggles[i] = !this.toggles[i];
    },
    clear: function () {
      this.prefecture = "";
    },
    getReq: function () {
      axios.get("/routes/mainController.php", {
        params: {
          prefecture: this.prefecture,
        }
      }).
      then(res => {
        console.log(res.data);
        this.weatherReports = res.data;
        this.regionName = res.data[0].prefecture;
        let toggles = {};
        //メモのidをkeyとしたboolean型の配列作成
        res.data.forEach(function (e) {
          let key = e.id;
          toggles[key] = false;
        });
        this.toggles = toggles;
      }).
      catch(error => {
        console.log(error);
        console.log(error.response);
        alert(error.response.data);
      })
    },
    postReq: function (i) {
      //ブラウザでポストする用のクラス
      let params = new URLSearchParams();
      params.append('memoId', i);
      params.append('memoText', this.memo[i]);
      params.append('prefecture', this.regionName)

      axios.post("/routes/mainController.php", params).
      then(res => {
        console.log("success");
        console.log(res);
        this.weatherReports = res.data;
      }).
      catch(error => {
        console.log(error);
        console.log("errorだよ");
      })
    }
  }
});