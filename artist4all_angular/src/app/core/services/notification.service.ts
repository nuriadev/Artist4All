import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { Notification } from '../models/notification';

@Injectable({
  providedIn: 'root',
})
export class NotificationService {
  constructor(private http: HttpClient) {}

  private url = 'http://localhost:81/user/';

  getNotifications(id: number): Observable<any> {
    return this.http.get(this.url + id + '/notification');
  }

  // TODO: pasar a patch
  notificationRead(id_notification: number, id_responsible: number): Observable<any> {
    return this.http.get(this.url + id_responsible + '/notification/' + id_notification);
  }

  removeNotification(id_notification: number, id_user: number): Observable<any> {
    return this.http.delete(this.url + id_user + '/notification/' + id_notification);
  }
}
