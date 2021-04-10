import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { Notification } from '../models/notification';

@Injectable({
  providedIn: 'root'
})
export class NotificationService {

  constructor(private http:HttpClient) { }

  private url = 'http://localhost:81';

  create(newNotification:Notification, token:string):Observable<any> {
    let createNotificationFormData:FormData = new FormData();

    return this.http.post(this.url, createNotificationFormData);
  }

  getMyNotifications(username:string, token:string):Observable<any> {
    return this.http.get(this.url + '/user/' + username + '/notification', { headers: new HttpHeaders({ 'Authorization': token })});
  }
}
