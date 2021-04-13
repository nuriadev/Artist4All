import { Component, OnInit } from '@angular/core';
import * as moment from 'moment';
import { AuthenticationService } from 'src/app/core/services/authentication.service';
import { NotificationService } from 'src/app/core/services/notification.service';
import { SessionService } from 'src/app/core/services/session.service';

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
    private _sessionService: SessionService
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
    this._notificationService.getNotifications(this.id, this.token).subscribe(
      (result) => {
        if (result != null) {
          result.forEach((element) => {
            element.notification_date = this.adaptDateOfNotification(
              element.notification_date
            );
          });
          this.notifications = result;
        }
      },
      (error) => {
        console.log(error);
      }
    );
  }

  logout() {
    this._authenticationService.logout(this.user.id, this.token).subscribe(
      (result) => {
        this._sessionService.logout();
      },
      (error) => {
        console.log(error);
      }
    );
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

  adaptDateOfNotification(upload_date: string) {
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
    else if (diffMonths > 1 && diffMonths < 12)
      adaptedDate = diffMonths + ' meses';
    else if (diffMonths == 1) adaptedDate = ' 1 mes';
    else if (diffWeeks > 1 && diffWeeks <= 4)
      adaptedDate = diffWeeks + ' semanas';
    else if (diffWeeks == 1) adaptedDate = ' 1 semana';
    else if (diffDays > 1 && diffDays < 7) adaptedDate = diffDays + 'd';
    else if (diffDays == 1) adaptedDate = ' 1d';
    else if (diffHours > 1 && diffHours < 24) adaptedDate = diffHours + 'h';
    else if (diffHours == 1) adaptedDate = ' 1h';
    else if (diffMinutes > 1 && diffMinutes < 60)
      adaptedDate = diffMinutes + 'm';
    else if (diffMinutes == 1) adaptedDate = ' 1m';
    else if (diffSeconds > 1 && diffSeconds < 60)
      adaptedDate = diffSeconds + 's';
    else if (diffSeconds <= 1) adaptedDate = 'actual';

    return adaptedDate;
  }
}
