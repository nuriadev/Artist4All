import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { matchingPasswords } from 'src/app/core/validators/password.validator';
import { Session } from 'src/app/core/models/session';
import { User } from 'src/app/core/models/user';
import { SessionService } from 'src/app/core/services/session.service';
import { UserService } from 'src/app/core/services/user.service';
import Swal from 'sweetalert2';

@Component({
  selector: 'app-user-settings-password',
  templateUrl: './user-settings-password.component.html',
  styleUrls: ['./user-settings-password.component.css'],
})
export class UserSettingsPasswordComponent implements OnInit {
  constructor(
    private _sessionService: SessionService,
    private _userService: UserService,
    private _formBuilder: FormBuilder,
  ) {}

  user = this._sessionService.getCurrentUser();
  token = this._sessionService.getCurrentToken();
  id: number = this.user.id;
  passwordForm: FormGroup;
  passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$#!%*?&])([A-Za-z\d$@#$!%*?&]|[^ ]){8,20}$/;
  isValidFormSubmitted = null;

  ngOnInit(): void {
    this.passwordForm = this._formBuilder.group({
      password: ['', [Validators.required, Validators.pattern(this.passwordPattern)]],
      passwordConfirm: ['', [Validators.required]]
    }, { validators: matchingPasswords });
  }

  checkMatchPasswords(): boolean {
    return (this.passwordForm.hasError('notMatching') && this.passwordForm.get('password').dirty && this.passwordForm.get('passwordConfirm').dirty);
  }

  showingPasswordHint: boolean = false;
  showPasswordHint() {
    if (!this.showingPasswordHint) this.showingPasswordHint = true;
    else this.showingPasswordHint = false;
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

  get password() { return this.passwordForm.get('password'); }
  get passwordConfirm() { return this.passwordForm.get('passwordConfirm'); }

  userEdited: User;
  editPassword(formValues) {
    this._userService.changePassword(this.user.id, formValues).subscribe(
        (result) => {
          this.userEdited = result.user;
          let userSession = new Session(result.token, this.userEdited);
          this._sessionService.setCurrentSession(userSession);
        }, (error) => {
          console.log(error);
    });
  }

  values: any;
  editingAnimation() {
    this.isValidFormSubmitted = false;
    if (this.passwordForm.invalid) { return; }
    Swal.fire({
      title: '??Est??s seguro de que quieres modificar la contrase??a?',
      text: 'Esta acci??n es irreversible.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#28a745',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Aceptar',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.isConfirmed) {
        this.isValidFormSubmitted = true;
        this.values = this.passwordForm.value;
        this.editPassword(this.values);
        Swal.fire({ title: 'Modificando contrase??a...', showConfirmButton: false, timerProgressBar: true, timer: 1000,
          didOpen: () => { Swal.showLoading(); },
        }).then((result) => {
          if (result.dismiss === Swal.DismissReason.timer) {
            Swal.fire({ title: 'Contrase??a modificada', position: 'center', icon: 'success',  showConfirmButton: false, timer: 1000, });
            this.passwordForm.reset();
          }
        });
      }
    });
  }
}
