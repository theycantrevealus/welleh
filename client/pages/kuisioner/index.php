<div class="container-fluid page__heading-container">
  <div class="page__heading d-flex align-items-center">
    <div class="flex">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Kuisioner</li>
        </ol>
      </nav>
      <h4><span id="nama-departemen"></span>Kuisioner</h4>
    </div>

  </div>
</div>


<div class="container-fluid page__container">
  <div class="row">
    <div class="col-lg-12 col-md-12">
      <div class="card">
        <div class="card-header card-header-large bg-white d-flex align-items-center">
          <h5 class="card-header__title flex m-0">Kuisioner</h5>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-12 mx-0">
              <form id="msform">
                <ul id="progressbar"></ul> <!-- fieldsets -->
                <div id="field_container"></div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<style>
  * {
    margin: 0;
    padding: 0
  }

  html {
    height: 100%
  }

  #grad1 {
    background-color: #9C27B0;
    background-image: linear-gradient(120deg, #FF4081, #81D4FA)
  }

  #msform {
    text-align: center;
    position: relative;
    margin-top: 20px
  }

  #msform fieldset .form-card {
    background: white;
    border: 0 none;
    border-radius: 0px;
    box-shadow: 0 2px 2px 2px rgba(0, 0, 0, 0.2);
    padding: 20px 40px 30px 40px;
    box-sizing: border-box;
    width: 94%;
    margin: 0 3% 20px 3%;
    position: relative
  }

  #msform fieldset {
    background: white;
    border: 0 none;
    border-radius: 0.5rem;
    box-sizing: border-box;
    width: 100%;
    margin: 0;
    padding-bottom: 20px;
    position: relative
  }

  #msform fieldset:not(:first-of-type) {
    display: none
  }

  #msform fieldset .form-card {
    text-align: left;
    color: #9E9E9E
  }

  #msform input,
  #msform textarea {
    padding: 0px 8px 4px 8px;
    border: none;
    border-bottom: 1px solid #ccc;
    border-radius: 0px;
    margin-bottom: 25px;
    margin-top: 2px;
    width: 100%;
    box-sizing: border-box;
    font-family: montserrat;
    color: #2C3E50;
    font-size: 16px;
    letter-spacing: 1px
  }

  #msform input:focus,
  #msform textarea:focus {
    -moz-box-shadow: none !important;
    -webkit-box-shadow: none !important;
    box-shadow: none !important;
    border: none;
    font-weight: bold;
    border-bottom: 2px solid skyblue;
    outline-width: 0
  }

  #msform .action-button,
  #msform .confirm {
    width: 100px;
    background: skyblue;
    font-weight: bold;
    color: white;
    border: 0 none;
    border-radius: 0px;
    cursor: pointer;
    padding: 10px 5px;
    margin: 10px 5px
  }

  #msform .action-button:hover,
  #msform .action-button:focus {
    box-shadow: 0 0 0 2px white, 0 0 0 3px skyblue
  }

  #msform .action-button-previous {
    width: 100px;
    background: #616161;
    font-weight: bold;
    color: white;
    border: 0 none;
    border-radius: 0px;
    cursor: pointer;
    padding: 10px 5px;
    margin: 10px 5px
  }

  #msform .action-button-previous:hover,
  #msform .action-button-previous:focus {
    box-shadow: 0 0 0 2px white, 0 0 0 3px #616161
  }

  select.list-dt {
    border: none;
    outline: 0;
    border-bottom: 1px solid #ccc;
    padding: 2px 5px 3px 5px;
    margin: 2px
  }

  select.list-dt:focus {
    border-bottom: 2px solid skyblue
  }

  .card {
    z-index: 0;
    border: none;
    border-radius: 0.5rem;
    position: relative
  }

  .fs-title {
    font-size: 25px;
    color: #2C3E50;
    margin-bottom: 10px;
    font-weight: bold;
    text-align: left
  }

  #progressbar {
    margin-bottom: 30px;
    overflow: hidden;
    color: lightgrey
  }

  #progressbar .active {
    color: #000000
  }

  #progressbar li {
    list-style-type: none;
    font-size: 12px;
    width: 25%;
    float: left;
    position: relative
  }

  #progressbar li strong {
    width: 50px;
    height: 50px;
    text-align: center;
    display: table-cell;
    vertical-align: middle;
    position: absolute;
    top: 15px;
    color: #fff !important;
    font-weight: bolder;
    font-size: 12pt;
    left: calc(50% - 25px);
  }

  #progressbar li:before {
    content: ""
  }

  #progressbar li:before {
    width: 50px;
    height: 50px;
    line-height: 45px;
    display: block;
    font-size: 18px;
    color: #ffffff;
    background: lightgray;
    border-radius: 50%;
    margin: 0 auto 10px auto;
    padding: 2px
  }

  #progressbar li:after {
    content: '';
    width: 100%;
    height: 2px;
    background: lightgray;
    position: absolute;
    left: 0;
    top: 25px;
    z-index: -1
  }

  #progressbar li.active:before,
  #progressbar li.active:after {
    background: skyblue
  }

  .radio-group {
    position: relative;
    margin-bottom: 25px
  }

  .radio {
    display: inline-block;
    width: 204;
    height: 104;
    border-radius: 0;
    background: lightblue;
    box-shadow: 0 2px 2px 2px rgba(0, 0, 0, 0.2);
    box-sizing: border-box;
    cursor: pointer;
    margin: 8px 2px
  }

  .radio:hover {
    box-shadow: 2px 2px 2px 2px rgba(0, 0, 0, 0.3)
  }

  .radio.selected {
    box-shadow: 1px 1px 2px 2px rgba(0, 0, 0, 0.1)
  }

  .fit-image {
    width: 100%;
    object-fit: cover
  }
</style>