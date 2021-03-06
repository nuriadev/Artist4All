import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { LoginUser } from 'src/app/core/models/loginUser';
import { Session } from 'src/app/core/models/session';
import { User } from 'src/app/core/models/user';
import { AuthenticationService } from 'src/app/core/services/authentication.service';
import { SessionService } from 'src/app/core/services/session.service';
import { FormControl, FormBuilder, Validators } from '@angular/forms';
import Swal from 'sweetalert2';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css'],
  providers: [AuthenticationService, SessionService],
})
export class LoginComponent implements OnInit {
  constructor(
    private _authenticationService: AuthenticationService,
    private _sessionService: SessionService,
    private _router: Router,
    private _formBuilder: FormBuilder
  ) {}

  ngOnInit(): void {}

  emailPattern = '^[a-z0-9._%+-]+@[a-z0-9.-]+.[a-z]{2,4}$';
  passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$#!%*?&])([A-Za-z\d$@#$!%*?&]|[^ ]){8,20}$/;
  isValidFormSubmitted = null;

  loginForm = this._formBuilder.group({
    email: ['', [Validators.required, Validators.pattern(this.emailPattern)]],
    password: ['',[Validators.required]]
  });

  get email() { return this.loginForm.get('email'); }
  get password() { return this.loginForm.get('password'); }

  user: User;
  loginError: boolean = false;
  timer;
  login() {
    clearInterval(this.timer);
    this.isValidFormSubmitted = false;
    if (this.loginForm.invalid) {
      return;
    }
    this.isValidFormSubmitted = true;
    let userLogged: LoginUser = this.loginForm.value;
    this._authenticationService.login(userLogged).subscribe(
      (result) => {
        if (result.token != null) {
          this.user = result.user;
          this.loggingAnimation();
          let userSession = new Session(result.token, this.user);
          this._sessionService.setCurrentSession(userSession);
        }
      }, (error) => {
        this.loginError = true;
        this.timer = setInterval(() => (this.loginError = false), 2000);
    });
  }

  showOrHidePassword() {
    let showIcon = document.getElementById('showIcon');
    let hideIcon = document.getElementById('hideIcon');
    let inputPassword: any = document.getElementById('inputPassword');
    if (showIcon.style.display === 'none') {
      showIcon.style.display = 'block';
      hideIcon.style.display = 'none';
      inputPassword.type = 'password';
    } else {
      hideIcon.style.display = 'block';
      showIcon.style.display = 'none';
      inputPassword.type = 'text';
    }
  }

  loggingAnimation() {
    const Toast = Swal.mixin({
      toast: true,
      position: 'top-left',
      showConfirmButton: false,
      timer: 1000,
      timerProgressBar: true,
      didOpen: (toast) => {
        Swal.showLoading(),
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer);
      },
    });
    Toast.fire({ title: 'Iniciando sesi??n...' }).then((result) => {
      if (result.dismiss === Swal.DismissReason.timer) {
        this._router.navigate(['/home']);
        const Toast = Swal.mixin({ toast: true, position: 'top-left', showConfirmButton: false, timer: 1000,
          didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
          },
        });
        Toast.fire({ title: 'Sesi??n iniciada', icon: 'success' });
      }
    });
  }
}
