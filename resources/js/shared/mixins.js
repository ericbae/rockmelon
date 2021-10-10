import commaNumber   from 'comma-number'
import { EventBus }  from '@/shared/event-bus'
import moment        from 'moment'
import slugify       from 'slugify'

export default {

  computed: {

    getAppName() {
      return window.appName;
    },

    getAppEmail() {
      return process.env.APP_EMAIL;
    },

    getAppUrl() {
      return window.appUrl;
    },

    getHelpUrl() {
      return window.helpUrl
    },

    getEnv() {
      return window.env
    },

    getBlogUrl() {
      return 'https://blog.newsy.co'
    },

    getCurrUser() {
      return window.currUser
    },

    getCurrSubscription() {
      return window.currSubscription
    },

    isAdmin() {
      return window.currUser != null && window.currUser.email == 'ericbae@gmail.com'
    },

    thisYear() {
      return moment().format("YYYY")
    },

    getStripeKey() {
      if(this.getEnv == 'local') {
        return 'pk_test_f2IRECtaPopVQxy8vyD50Oq9'
      }

      else {
        return 'pk_live_OXulLqIMl0Ey1aj42257sqVU'
        // return process.env.MIX_STRIPE_KEY
      }
    },

    getDefaultQueryString() {
      return 'ZmM4rtgJE8';
    },

    getConfig() {
      return window.config
    }
  },

  methods : {

    copyText(text) {
      this.$copyText(text).then(function (e) {
        EventBus.$emit('notification-event', {
          'type'   : 'success', 
          'message': 'Copied to clipboard.'
        })
        // alert('Copied')
        // console.log(e)
      }, function (e) {
        // alert('Can not copy')
        // console.log(e)
      })
    },
    
    getBorderColours() {
      return ['#008cff', '#00b123', '#ec720a', '#9b01e7', '#996633', '#108080', '#ff2575', '#0a2285', '#74ca55', '#6e716c']
    },

    addComparison() {
      this.$modal.show('add-comparison-one-modal')
    },

    getSlug(text) {
      return slugify(text, {
        lower : true,
        strict: true
      })
    },

    isAreaVisible(area) {
      if(window.currSite != null) {
        return _.indexOf(window.layouts[window.currSite.designSettings.layout].areas, area) > -1
      }
    },
    
    postActivity(params) {
      if(!this.checkIfBot(navigator.userAgent)) {        
        this.apiPost({
          logName    : 'admin',
          siteId     : params.siteId == null ? null : params.siteId,
          subjectType: params.subjectType == null ? null : params.subjectType,
          subjectId  : params.subjectId == null ? null : params.subjectId,
          causerType : params.causerType == null ? null : params.causerType,
          causerId   : params.causerId == null ? null : params.causerId,
          description: params.description,
          properties : params.properties == null ? null : params.properties,
          timezone   : Intl.DateTimeFormat().resolvedOptions().timeZone,
          useragent  : window.currHeaders != null && window.currHeaders['user-agent'] != null && window.currHeaders['user-agent'].length > 0 ? window.currHeaders['user-agent'][0] : null
        }, '/auth/activity', (data) => {

        })
      }
    },

    checkIfBot(userAgent) {
      var botPattern = "(googlebot\/|Googlebot-Mobile|Googlebot-Image|Google favicon|Mediapartners-Google|bingbot|slurp|java|wget|curl|Commons-HttpClient|Python-urllib|libwww|httpunit|nutch|phpcrawl|msnbot|jyxobot|FAST-WebCrawler|FAST Enterprise Crawler|biglotron|teoma|convera|seekbot|gigablast|exabot|ngbot|ia_archiver|GingerCrawler|webmon |httrack|webcrawler|grub.org|UsineNouvelleCrawler|antibot|netresearchserver|speedy|fluffy|bibnum.bnf|findlink|msrbot|panscient|yacybot|AISearchBot|IOI|ips-agent|tagoobot|MJ12bot|dotbot|woriobot|yanga|buzzbot|mlbot|yandexbot|YandexMobileBot|purebot|Linguee Bot|Voyager|CyberPatrol|voilabot|baiduspider|citeseerxbot|spbot|twengabot|postrank|turnitinbot|scribdbot|page2rss|sitebot|linkdex|Adidxbot|blekkobot|ezooms|dotbot|Mail.RU_Bot|discobot|heritrix|findthatfile|europarchive.org|NerdByNature.Bot|sistrix crawler|ahrefsbot|Aboundex|domaincrawler|wbsearchbot|summify|ccbot|edisterbot|seznambot|ec2linkfinder|gslfbot|aihitbot|intelium_bot|facebookexternalhit|yeti|RetrevoPageAnalyzer|lb-spider|sogou|lssbot|careerbot|wotbox|wocbot|ichiro|DuckDuckBot|lssrocketcrawler|drupact|webcompanycrawler|acoonbot|openindexspider|gnam gnam spider|web-archive-net.com.bot|backlinkcrawler|coccoc|integromedb|content crawler spider|toplistbot|seokicks-robot|it2media-domain-crawler|ip-web-crawler.com|siteexplorer.info|elisabot|proximic|changedetection|blexbot|arabot|WeSEE:Search|niki-bot|CrystalSemanticsBot|rogerbot|360Spider|psbot|InterfaxScanBot|Lipperhey SEO Service|CC Metadata Scaper|g00g1e.net|GrapeshotCrawler|urlappendbot|brainobot|fr-crawler|binlar|SimpleCrawler|Livelapbot|Twitterbot|cXensebot|smtbot|bnf.fr_bot|A6-Indexer|ADmantX|Facebot|Twitterbot|OrangeBot|memorybot|AdvBot|MegaIndex|SemanticScholarBot|ltx71|nerdybot|xovibot|BUbiNG|Qwantify|archive.org_bot|Applebot|TweetmemeBot|crawler4j|findxbot|SemrushBot|yoozBot|lipperhey|y!j-asr|Domain Re-Animator Bot|AddThis)";
      var re = new RegExp(botPattern, 'i');
      return (re.test(userAgent))
    },

    checkConfirm(url) {
      if(window.currUser != null && window.currUser.confirmed_at == null) {
        this.$modal.show('confirm-registration-modal')
      }

      else {
        if(url != null) {
          window.location.href = url
        }
      }
    },

    openNewSiteModal() {
      this.$modal.show('new-site-one-modal')
    },

    formatDate(value, from, to, addServerTime = false) {
      let m = null

      if(from == 'unix-seconds') {
        m = moment.unix(value)
      }

      else if(from == 'unix-milliseconds') {
        m = moment(value)
      }

      else {
        m = moment(value, from)
      }

      if(addServerTime) {
        if(window.tdbsc < 0) {
          m = m.add(Math.abs(window.tdbsc), 'hours')  
        }
        
        else {
          m = m.subtract(Math.abs(window.tdbsc), 'hours')
        }
      }

      if(to == 'ago') {
        return m.fromNow()
      }

      else if(to == 'full') {
        return m.format("ddd, MMM Do YYYY, h:mm:ss a")
      }

      else {
        return m.format(to)
      }
    },

    toggleRightDrawer(contentType) {
      EventBus.$emit('toggle-right-drawer', contentType)
    },

    createNewSite() {
      if(this.getCurrUser != null) {
        if(this.getCurrUser.confirmed_at != null) {
          this.$modal.show('new-site-modal')
        } else {
          window.location.href = "/not-confirmed"
        }
      }

      else {
        window.location.href = "/login?msg=1"
      }
    },

    showHelp(what) {

    },

    ucfirst(text) {
      return text.charAt(0).toUpperCase() + text.slice(1)
    },

    getLinkGroupUrl(linkGroup) {
      return this.getAppUrl + "/rl/" + linkGroup.identifier
    },

    getEnvParams(site) {
      if(this.getEnv == 'production') {
        return ''
      } else {
        return '?siteId=' + site.id
      }
    },

    isEmailValid (email) {
      return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)
    },

    formatNumber(value) {
      return commaNumber(value)
    },

    shorten(text, length) {
      if(text.length > length) {
        return text.substr(0, length) + '..'
      }

      return text
    },

    getVideoType(value) {
      if(value.indexOf('youtube.') > -1) {
        return 'youtube'
      }

      else if(value.indexOf('youtu.be') > -1) {
        return 'youtube'
      }

      else if(value.indexOf('vimeo.') > -1) {
        return 'vimeo'
      }
    },

    getYoutubeVideoId(url) {
      var a, ampersandPosition, b, videoId;
      if ((url != null) && url.length > 0) {
        if (url.indexOf('youtu.be') > -1) {
          a = url.split('?')[0];
          b = a.split('/');
          return b[b.length - 1];
        } else {
          let arr = url.split('v=');

          if(arr.length > 1) {
            videoId = arr[1];
            ampersandPosition = videoId.indexOf('&')
            
            if(ampersandPosition !== -1) {
              videoId = videoId.substring(0, ampersandPosition)
            }

            if((videoId != null) && videoId.length > 0) {
              return videoId;
            }
          }
        }
      }

      return null
    },

    getVimeoUrl(url){
      var vimeoRegex = /(?:vimeo)\.com.*(?:videos|video|channels|)\/([\d]+)/i;
      var parsed = url.match(vimeoRegex);
      if(parsed != null && parsed.length > 0) {
        return "//player.vimeo.com/video/" + parsed[1];
      }
    },

    apiGet(params, url, callback) {
      axios.get(url, {
        params,
      }).then(response => {
        callback(response.data)
      }).catch(e => {

      })
    },

    apiPost(params, url, callback) {
      axios.post(url, params, {
        headers : {
          // 'Authorization' : 'Bearer ' + localStorage.getItem('gb-jwt')
        }
      }).then(response => {
        if(response.data != null) {
          callback(response.data)
        } else {
          callback()
        }
      }).catch(e => {
        console.log(e)
        if(e.response != null && e.response.data != null) {
          callback(e.response.data)
        }
      })
    },

    apiDelete(params, url, callback) {
      axios.delete(url, {
        params, 
        headers: {
          'Authorization' : 'Bearer ' + localStorage.getItem('gb-jwt')
        }
      }).then(response => {
        callback()
      }).catch(e => {
        // this.loading = false;
      })
    },
  }
}