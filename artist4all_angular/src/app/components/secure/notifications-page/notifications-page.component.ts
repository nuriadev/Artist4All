import { Component, OnInit } from '@angular/core';
import * as moment from 'moment';
import { NotificationService } from 'src/app/core/services/notification.service';
import { SessionService } from 'src/app/core/services/session.service';

@Component({
  selector: 'app-notifications-page',
  templateUrl: './notifications-page.component.html',
  styleUrls: ['./notifications-page.component.css']
})
export class NotificationsPageComponent implements OnInit {

  constructor(
    private _sessionService: SessionService,
    private _notificationService: NotificationService
  ) { }

  user = this._sessionService.getCurrentUser();
  token = this._sessionService.getCurrentToken();
  id: number;

  notifications: Array<Notification>;
  ngOnInit(): void {
    this.id = this.user.id;
    this._notificationService.getNotifications(this.id).subscribe(
      (result) => {
        if (result != null) {
          result.forEach((notification) => {
            if (notification.typeNotification == 1) notification.bodyNotification = 'ha empezado a seguirte';
            if (notification.typeNotification == 2) notification.bodyNotification = 'te ha enviado una solicitud de amistad';
            if (notification.typeNotification == 3) notification.bodyNotification = 'ha aceptado tu solicitud. Ahora ya puedes ver sus publicaciones';
            notification.notification_date = this.adaptNotificationDate(notification.notification_date);
          });
          this.notifications = result;
        }
      },
      (error) => {
        console.log(error);
      });
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

}
