import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { matchingPasswords } from 'src/app/core/validators/password.validator';
import { Session } from 'src/app/core/models/session';
import { User } from 'src/app/core/models/user';
import { SessionService } from 'src/app/core/services/session.service';
import { UserService } from 'src/app/core/services/user.service';

@Component({
  selector: 'app-user-settings-password',
  templateUrl: './user-settings-password.component.html',
  styleUrls: ['./user-settings-password.component.css'],
})
export class UserSettingsPasswordComponent implements OnInit {
  constructor(
    private _sessionService: SessionService,
    private _userService: UserService,
    private _router: Router,
    private _formBuilder:FormBuilder
  ) { }

  user = this._sessionService.getCurrentUser();
  token = this._sessionService.getCurrentToken();
  id: number = this.user.id;
  passwordForm: FormGroup;
  passwordPattern='[A-Za-z0-9 ]+';
  isValidFormSubmitted = null;

  ngOnInit(): void {
    this.passwordForm = this._formBuilder.group({
      password: ['', [Validators.required, Validators.pattern(this.passwordPattern)]],
      passwordConfirm: ['', [Validators.required]],
    }, { validators: matchingPasswords });
  }

  checkMatchPasswords():  boolean  {
    return this.passwordForm.hasError('notMatching')  &&
      this.passwordForm.get('password').dirty &&
      this.passwordForm.get('passwordConfirm').dirty
  }

  showOrHidePassword() {
    let showIcon = document.getElementById('showIcon');
    let hideIcon = document.getElementById('hideIcon');
    let inputPassword:any = document.getElementById('inputPassword');
    if (showIcon.style.display === 'none') {
      showIcon.style.display = "block";
      hideIcon.style.display = 'none';
      inputPassword.type = 'password';
    } else {
      hideIcon.style.display = "block";
      showIcon.style.display = "none";
       inputPassword.type = 'text';
    }
  }

  get password() { return this.passwordForm.get('password'); }
  get passwordConfirm() { return this.passwordForm.get('passwordConfirm'); }

  userEdited:User;
  // todo añadir notificacion de cambio correcto
  editPassword() {
    this.isValidFormSubmitted = false;
    if (this.passwordForm.invalid) {
       return;
    }
    this.isValidFormSubmitted = true;
    let values:any = this.passwordForm.value;
    this._userService.editPassword(this.user.id, values, this.token).subscribe(
      (result) => {
        this.userEdited = result.user;
        let userSession = new Session(result.token, this.userEdited);
        this._sessionService.setCurrentSession(userSession);
        this.passwordForm.reset();
      }, (error) => {
        console.log(error);
      }
    )
  }
}
