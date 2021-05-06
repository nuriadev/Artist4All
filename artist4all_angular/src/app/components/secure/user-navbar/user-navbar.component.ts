import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import * as moment from 'moment';
import { AuthenticationService } from 'src/app/core/services/authentication.service';
import { NotificationService } from 'src/app/core/services/notification.service';
import { SessionService } from 'src/app/core/services/session.service';
import { UserService } from 'src/app/core/services/user.service';
import Swal from
'sweetalert2';
import {
  MatSnackBar,
  MatSnackBarHorizontalPosition,
  MatSnackBarVerticalPosition,
} from '@angular/material/snack-bar';
import { User } from 'src/app/core/models/user';

@Component({
  selector: 'app-user-navbar',
  templateUrl: './user-navbar.component.html',
  styleUrls: ['./user-navbar.component.css'],
  providers: [AuthenticationService],
})
export class UserNavbarComponent implements OnInit {
  constructor(
    private _authenticationService: AuthenticationService,
    private _notificationService: NotificationService,
    private _sessionService: SessionService,
    private _userService: UserService,
    private _router: Router,
    private _snackBar: MatSnackBar
  ) {}

  user = this._sessionService.getCurrentUser();
  token = this._sessionService.getCurrentToken();
  id: number;
  name: string;
  surname1: string;
  surname2: string;
  email: string;
  username: string;
  password: string;
  imgAvatar: FileList;
  panelOpenState: boolean = false;

  notifications: Array<Notification>;
  ngOnInit(): void {
    this.id = this.user.id;
    this.name = this.user.name;
    this.surname1 = this.user.surname1;
    this.surname2 = this.user.surname2;
    this.email = this.user.email;
    this.username = this.user.username;
    this.password = this.user.password;
    this.imgAvatar = this.user.imgAvatar;
      this._notificationService.getNotifications(this.id).subscribe(
      (result) => {
        if (result != null) {
          result.forEach((notification) => {
            if(notification.typeNotification == 1) notification.bodyNotification = 'ha empezado a seguirte';
            if(notification.typeNotification == 2) notification.bodyNotification = 'te ha enviado una solicitud de amistad';
            if(notification.typeNotification == 3) notification.bodyNotification = 'ha aceptado tu solicitud. Ahora ya puedes ver sus publicaciones';
            notification.notification_date = this.adaptNotificationDate(notification.notification_date);
          });
          this.notifications = result;
        }
      },
      (error) => {
        console.log(error);
      });
  }

  usersReceived: Array<User>;
  searchUser(event) {
    let searchedPattern = event.target.value;
    this._userService.searchUser(searchedPattern).subscribe(
      (result) => {
        this.usersReceived = result;
        console.log(this.usersReceived);
      }, (error) => {
        console.log(error);
    });
  }

  logout() {
    this._authenticationService.logout(this.user.id).subscribe(
      (result) => {
        this._sessionService.logout();
      }, (error) => {
        console.log(error);
    });
  }

  logoutAlert() {
    Swal.fire({
      title: '¿Estás seguro de que quieres cerrar la sesión?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Cerrar sesión',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.isConfirmed) {
        const Toast = Swal.mixin({ toast: true, position: 'top-left', showConfirmButton: false, timer: 1000, timerProgressBar: true,
          didOpen: (toast) => {
            Swal.showLoading(),
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
          },
        });
        Toast.fire({ title: 'Cerrando sessión...' }).then((result) => {
          if (result.dismiss === Swal.DismissReason.timer) {
            const Toast = Swal.mixin({ toast: true, position: 'top-left', showConfirmButton: false, timer: 1000,
              didOpen: (toast) => {
                  toast.addEventListener('mouseenter', Swal.stopTimer);
                  toast.addEventListener('mouseleave', Swal.resumeTimer);
              }
            });
            Toast.fire({ title: 'Sesión cerrada', icon: 'success' });
          }
          this.logout();
          this._router.navigate(['']);
        });
      }
    });
  }

  isDisplayMobile = false;
  toggleMobileMenu() {
    if (!this.isDisplayMobile) {
      document.getElementById('mobile-menu2').style.display = "block";
      document.getElementById('buttonMenu').style.display = "none";
      document.getElementById('cross').style.display = "block";
      this.isDisplayMobile = true;
    } else {
      document.getElementById('mobile-menu2').style.display = "none";
      document.getElementById('buttonMenu').style.display = "block";
      document.getElementById('cross').style.display = "none";
      this.isDisplayMobile = false;
    }
  }

  isDisplayedNotifications = false;
  toggleNotifications() {
    if (!this.isDisplayedNotifications) {
      document.getElementById('menu-notifications').style.display = 'block';
      document.getElementById('menu-toggle').style.display = 'none';
      this.isDisplayedNotifications = true;
      this.isDisplayedMenu = false;
    } else {
      document.getElementById('menu-notifications').style.display = 'none';
      this.isDisplayedNotifications = false;
    }
  }
  isDisplayedMenu = false;
  toggleMenuProfile() {
    if (!this.isDisplayedMenu) {
      document.getElementById('menu-toggle').style.display = 'block';
      document.getElementById('menu-notifications').style.display = 'none';
      this.isDisplayedMenu = true;
      this.isDisplayedNotifications = false;
    } else {
      document.getElementById('menu-toggle').style.display = 'none';
      this.isDisplayedMenu = false;
    }
  }

  adaptNotificationDate(upload_date: string) {
    var currentDate = moment(new Date());
    var notification_date = moment(upload_date);
    let diffYears = currentDate.diff(notification_date, 'years');
    let diffMonths = currentDate.diff(notification_date, 'months');
    let diffDays = currentDate.diff(notification_date, 'days');
    let diffWeeks = currentDate.diff(notification_date, 'weeks');
    let diffHours = currentDate.diff(notification_date, 'hours');
    let diffMinutes = currentDate.diff(notification_date, 'minutes');
    let diffSeconds = currentDate.diff(notification_date, 'seconds');
    let adaptedDate;
    if (diffYears > 1) adaptedDate = diffYears + ' años';
    else if (diffYears == 1) adaptedDate = ' 1 año';
    else if (diffMonths > 1 && diffMonths < 12) adaptedDate = diffMonths + ' meses';
    else if (diffMonths == 1) adaptedDate = ' 1 mes';
    else if (diffWeeks > 1 && diffWeeks <= 4) adaptedDate = diffWeeks + ' semanas';
    else if (diffWeeks == 1) adaptedDate = ' 1 semana';
    else if (diffDays > 1 && diffDays < 7) adaptedDate = diffDays + 'd';
    else if (diffDays == 1) adaptedDate = ' 1d';
    else if (diffHours > 1 && diffHours < 24) adaptedDate = diffHours + 'h';
    else if (diffHours == 1) adaptedDate = ' 1h';
    else if (diffMinutes > 1 && diffMinutes < 60) adaptedDate = diffMinutes + 'm';
    else if (diffMinutes == 1) adaptedDate = ' 1m';
    else if (diffSeconds > 1 && diffSeconds < 60) adaptedDate = diffSeconds + 's';
    else if (diffSeconds <= 1) adaptedDate = 'actual';
    return adaptedDate;
  }

  id_follow: number;
  acceptOrDeclineRequest(id_notification: number, id_follower: number, index: number, status_follow: number) {
    this._userService.isFollowingThatUser(id_follower, this.user.id).subscribe(
      (result) => {
        if (result != null) {
          this.id_follow = result.id;
          this._userService.updateFollowRequest(this.id_follow, id_follower, this.user.id, status_follow).subscribe(
            (result) => {
              this.notificationRead(id_notification, index);
              if (status_follow == 3) this.message = 'Solicitud aceptada.';
              else this.message = 'Solicitud rechazada.';
              this.openSnackBar(this.message);
            }, (error) => {
              console.log(error);
          });
        }
      }, (error) => {
        console.log(error);
      });
  }

  notificationRead(id_notification: number, index: number) {
    this._notificationService.notificationRead(id_notification, this.user.id).subscribe();
    this.notifications.splice(index, 1);
    this.message = 'Notificación leída.';
    this.openSnackBar(this.message);
  }

  removeNotification(id_notification: number, index: number) {
    this._notificationService.removeNotification(id_notification, this.user.id).subscribe();
    this.notifications.splice(index, 1);
    this.message = 'Notificación eliminada.';
    this.openSnackBar(this.message);
  }

  message: string;
  horizontalPosition: MatSnackBarHorizontalPosition = 'right';
  verticalPosition: MatSnackBarVerticalPosition = 'bottom';
  openSnackBar(message: string) {
    this._snackBar.open(message, 'OK', { duration: 2000, horizontalPosition: this.horizontalPosition, verticalPosition: this.verticalPosition });
  }
}
