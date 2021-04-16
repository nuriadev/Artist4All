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

  getNotifications(id: number, token: string): Observable<any> {
    return this.http.get(this.url + id + '/notification', { headers: new HttpHeaders({ Authorization: token }) });
  }

  // TODO: pasar a patch
  notificationRead(id_notification: number, id_responsible: number, token: string): Observable<any> {
    return this.http.post(this.url + id_responsible + '/notification/' + id_notification, { headers: new HttpHeaders({ Authorization: token }) });
  }

  removeNotification(id_notification: number, id_user: number, token: string): Observable<any> {
    return this.http.delete(this.url + id_user + '/notification/' + id_notification, { headers: new HttpHeaders({ Authorization: token }) });
  }
}
