
<style type="text/css">
:root {
  --input-padding-x: 1.5rem;
  --input-padding-y: .75rem;
}

body {
  /* background: #007bff;
  background: linear-gradient(to right, #0062E6, #33AEFF); */
  background: #56ABE8;
  background: linear-gradient(to right, #565EE8, #5EE6FF);
}

.card-signin {
  border: 0;
  border-radius: 1rem;
  box-shadow: 0 0.5rem 1rem 0 rgba(0, 0, 0, 0.1);
}

.card-signin .card-title {
  margin-bottom: 2rem;
  font-weight: 300;
  font-size: 1.5rem;
}

.card-signin .card-body {
  padding: 2rem;
}

.form-signin {
  width: 100%;
}

.form-signin .btn {
  font-size: 80%;
  border-radius: 5rem;
  letter-spacing: .1rem;
  font-weight: bold;
  padding: 1rem;
  transition: all 0.2s;
}

.form-label-group {
  position: relative;
  margin-bottom: 1rem;
}

.form-label-group input {
  height: auto;
  border-radius: 2rem;
}

.form-label-group>input,
.form-label-group>label {
  padding: var(--input-padding-y) var(--input-padding-x);
}

.form-label-group>label {
  position: absolute;
  top: 0;
  left: 0;
  display: block;
  width: 100%;
  margin-bottom: 0;
  /* Override default `<label>` margin */
  line-height: 1.5;
  color: #495057;
  border: 1px solid transparent;
  border-radius: .25rem;
  transition: all .1s ease-in-out;
}

.form-label-group input::-webkit-input-placeholder {
  color: transparent;
}

.form-label-group input:-ms-input-placeholder {
  color: transparent;
}

.form-label-group input::-ms-input-placeholder {
  color: transparent;
}

.form-label-group input::-moz-placeholder {
  color: transparent;
}

.form-label-group input::placeholder {
  color: transparent;
}

.form-label-group input:not(:placeholder-shown) {
  padding-top: calc(var(--input-padding-y) + var(--input-padding-y) * (2 / 3));
  padding-bottom: calc(var(--input-padding-y) / 3);
}

.form-label-group input:not(:placeholder-shown)~label {
  padding-top: calc(var(--input-padding-y) / 3);
  padding-bottom: calc(var(--input-padding-y) / 3);
  font-size: 12px;
  color: #777;
}

.btn-google {
  color: white;
  text-align:center;
  background-color: #ea4335;
  border-radius: 25px;
  width: 50px;
  height: 50px;
}

.btn-facebook {
  color: white;
  text-align:center;
  background-color: #3b5998;
  border-radius: 25px;
  width: 50px;
    height: 50px;
}
.overlay{
    position: absolute;

    z-index: 10;
    width: 100%;
    height: 40%;
    bottom:-10%;
    background-color: white;
    transform: skewY(10deg);
    border-radius : 1rem;

}
.card-change {
  border: 0;
  /* margin-top:40%!important; */
  border-radius : 1rem;
  box-shadow: 0 0.7rem 1rem 0 rgba(0, 0, 0, 0.3);
  background-color: transparent;
}

.card-change  .card-title {
  margin-bottom: 2rem;
  font-weight: 300;
  font-size: 1.5rem;
}

.card-change  .card-body {
  padding: 2rem;
}
.cardBackground{
  background-image: url('img/115.png');
  background-repeat: no-repeat;
  background-size: 100% 100%;
  background-position:center;
}

.wrapper {
    display: flex;
    width: 100%;
    align-items: stretch;
}
</style>
