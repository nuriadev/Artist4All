import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { Session } from 'src/app/core/models/session';
import { User } from 'src/app/core/models/user';
import { SessionService } from 'src/app/core/services/session.service';
import { UserService } from 'src/app/core/services/user.service';
import {
  MatSnackBar,
  MatSnackBarConfig,
  MatSnackBarHorizontalPosition,
  MatSnackBarVerticalPosition,
} from '@angular/material/snack-bar';
import Swal from 'sweetalert2';
import { AuthenticationService } from 'src/app/core/services/authentication.service';
@Component({
  selector: 'app-user-settings-account',
  templateUrl: './user-settings-account.component.html',
  styleUrls: ['./user-settings-account.component.css'],
})
export class UserSettingsAccountComponent implements OnInit {
  constructor(
    private _sessionService: SessionService,
    private _userService: UserService,
    private _snackBar: MatSnackBar,
    private _router: Router
  ) {}

  user = this._sessionService.getCurrentUser();
  token = this._sessionService.getCurrentToken();
  id: number = this.user.id;

  sliderSwitchPrivate = document.getElementById('sliderSwitchPrivate');
  toggleButtonPrivate = document.getElementById('toggleButtonPrivate');
  staredPrivate: boolean;
  ngOnInit(): void {
    let sliderSwitchPrivate = document.getElementById('sliderSwitchPrivate');
    let toggleButtonPrivate = document.getElementById('toggleButtonPrivate');
    if (this.user.isPrivate == 0) {
      sliderSwitchPrivate.style.marginLeft = '0px';
      toggleButtonPrivate.style.backgroundColor = 'rgba(229, 231, 235)';
      this.isPrivate = false;
      this.staredPrivate = false;
    } else if (this.user.isPrivate == 1) {
      sliderSwitchPrivate.style.marginLeft = '20px';
      toggleButtonPrivate.style.backgroundColor = '#2196F3';
      this.isPrivate = true;
      this.staredPrivate = true;
    }
  }

  isPrivate: boolean;
  userEdited: User;
  alternatePrivateAccount() {
    let sliderSwitchPrivate = document.getElementById('sliderSwitchPrivate');
    let toggleButtonPrivate = document.getElementById('toggleButtonPrivate');
    if (!this.staredPrivate) {
      if (!this.isPrivate) {
        sliderSwitchPrivate.style.transform = 'translateX(20px)';
        toggleButtonPrivate.style.backgroundColor = '#2196F3';
        this.user.isPrivate = 1;
        this.message = 'Modo privado activado.';
        this.isPrivate = true;
      } else {
        sliderSwitchPrivate.style.transform = 'translateX(0px)';
        toggleButtonPrivate.style.backgroundColor = 'rgba(229, 231, 235)';
        this.user.isPrivate = 0;
        this.message = 'Modo privado desactivado.';
        this.isPrivate = false;
      }
    } else {
      if (!this.isPrivate) {
        sliderSwitchPrivate.style.transform = 'translateX(0.40px)';
        toggleButtonPrivate.style.backgroundColor = '#2196F3';
        this.user.isPrivate = 1;
        this.message = 'Modo privado activado.';
        this.isPrivate = true;
      } else {
        sliderSwitchPrivate.style.transform = 'translateX(-20px)';
        toggleButtonPrivate.style.backgroundColor = 'rgba(229, 231, 235)';
        this.user.isPrivate = 0;
        this.message = 'Modo privado desactivado.';
        this.isPrivate = false;
      }
    }
    this.openSnackBar(this.message);
    this._userService.privateAccountSwitcher(this.user).subscribe(
      (result) => {
        this.userEdited = result.user;
        let userSession = new Session(result.token, this.userEdited);
        this._sessionService.setCurrentSession(userSession);
      }, (error) => {
        console.log(error);
    });
  }

  deactivateAccountAnimation() {
    Swal.fire({
      title: '??Est??s seguro de que quieres darte de baja?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#28a745',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Aceptar',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.isConfirmed) {
        this.deactivateAccount();
        Swal.fire({ title: 'Dando de baja...', showConfirmButton: false, timerProgressBar: true, timer: 1000,
          didOpen: () => { Swal.showLoading(); },
        }).then((result) => {
          if (result.dismiss === Swal.DismissReason.timer) {
            Swal.fire({
              title: 'Dado de baja correctamente',
              text: 'Esperamos tu regreso',
              position: 'center',
              icon: 'success',
              showConfirmButton: false,
              timer: 1000,
            }).then((result) => {
              const Toast = Swal.mixin({ toast: true, position: 'top-left', showConfirmButton: false, timer: 1000, timerProgressBar: true,
              didOpen: (toast) => {
                Swal.showLoading(),
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
              },
              });
              Toast.fire({ title: 'Cerrando sessi??n...' }).then((result) => {
                if (result.dismiss === Swal.DismissReason.timer) {
                  const Toast = Swal.mixin({ toast: true, position: 'top-left', showConfirmButton: false, timer: 1000,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                  });
                  Toast.fire({ title: 'Sessi??n cerrada', icon: 'success' });
                }
                this._sessionService.logout();
                this._router.navigate(['']);
              });
            });
          }
        });
      }
    });
  }

  deactivateAccount() {
    this._userService.deactivateAccount(this.user).subscribe();
  }

  message: string;
  horizontalPosition: MatSnackBarHorizontalPosition = 'right';
  verticalPosition: MatSnackBarVerticalPosition = 'bottom';
  openSnackBar(message: string) {
    this._snackBar.open(message, 'OK', { duration: 1000, horizontalPosition: this.horizontalPosition, verticalPosition: this.verticalPosition });
  }
}
