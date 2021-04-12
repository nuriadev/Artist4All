import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { Session } from 'src/app/core/models/session';
import { SessionService } from 'src/app/core/services/session.service';
import { User } from '../../../core/models/user';
import { UserService } from '../../../core/services/user.service';
import { FormControl, FormBuilder, Validators, FormGroup } from '@angular/forms';
import { matchingPasswords } from 'src/app/core/validators/password.validator';

@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.css'],
  providers: [SessionService, UserService],
})
export class RegisterComponent implements OnInit {
  constructor(
    private _userService: UserService,
    private _sessionService: SessionService,
    private _router: Router,
    private _formBuilder: FormBuilder
  ) {}

  registerForm: FormGroup;
  namePattern ="[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]{2,48}";
  surname1Pattern ="[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]{2,64}";
  surname2Pattern ="[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]{2,64}";
  usernamePattern = '^[a-z0-9_ ]{5,20}$'; // letras _ num min length 5 max 20
  emailPattern = '^[a-z0-9._%+-]+@[a-z0-9.-]+.[a-z]{2,4}$';
  passwordPattern = '[A-Za-z0-9 ]+';
  // TODO: Cambiar una vez finalizado proyecto
  //passwordPattern='(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}'; password must contain 8 or more characters that are of at least one number, and one uppercase and lowercase letter
  isValidFormSubmitted = null;

  // TODO: devolver mensaje de error en caso de que el username o el gmail ya está cogido
  ngOnInit(): void {
    this.registerForm = this._formBuilder.group({
        name: ['', [Validators.required, Validators.pattern(this.namePattern)]],
        surname1: ['', [Validators.required, Validators.pattern(this.surname1Pattern)]],
        surname2: ['', [Validators.required, Validators.pattern(this.surname2Pattern)]],
        email: ['', [Validators.required, Validators.pattern(this.emailPattern)]],
        username: ['', [Validators.required, Validators.pattern(this.usernamePattern)]],
        password: ['', [Validators.required, Validators.pattern(this.passwordPattern)]],
        passwordConfirm: ['', [Validators.required]],
        isArtist: ['', [Validators.required]],
        isPrivate: [0],
      }, { validators: matchingPasswords });
  }

  checkMatchPasswords(): boolean {
    return (
      this.registerForm.hasError('notMatching') &&
      this.registerForm.get('password').dirty &&
      this.registerForm.get('passwordConfirm').dirty
    );
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

  get name() {
    return this.registerForm.get('name');
  }
  get surname1() {
    return this.registerForm.get('surname1');
  }
  get surname2() {
    return this.registerForm.get('surname2');
  }
  get email() {
    return this.registerForm.get('email');
  }
  get username() {
    return this.registerForm.get('username');
  }
  get password() {
    return this.registerForm.get('password');
  }
  get passwordConfirm() {
    return this.registerForm.get('passwordConfirm');
  }

  user: User;
  registerError: boolean = false;
  timer;
  register() {
    clearInterval(this.timer);
    this.isValidFormSubmitted = false;
    if (this.registerForm.invalid) {
      return;
    }
    this.isValidFormSubmitted = true;
    let userRegistered: User = this.registerForm.value;
    this._userService.register(userRegistered).subscribe(
      (result) => {
        if (result['token'] != null) {
          this.user = result.user;
          let userSession = new Session(result.token, this.user);
          this._sessionService.setCurrentSession(userSession);
          this._router.navigate(['/home']);
        }
      },
      (error) => {
        this.registerError = true;
        this.timer = setInterval(() => (this.registerError = false), 2000);
      }
    );
  }
}
