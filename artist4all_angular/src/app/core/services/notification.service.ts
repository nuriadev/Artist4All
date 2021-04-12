import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { Notification } from '../models/notification';

@Injectable({
  providedIn: 'root',
})
export class NotificationService {
  constructor(private http: HttpClient) {}

  private url = 'http://localhost:81';

  getNotifications(id: number, token: string): Observable<any> {
    return this.http.get(this.url + '/user/' + id + '/notification', {
      headers: new HttpHeaders({ Authorization: token }),
    });
  }
}
