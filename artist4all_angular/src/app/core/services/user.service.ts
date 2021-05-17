import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { User } from '../models/user';
import { Session } from '../models/session';

@Injectable()
export class UserService {
  constructor(private http: HttpClient) {}

  private url = 'http://localhost:81';

  register(newUser: User): Observable<any> {
    let newForm: FormData = new FormData();
    newForm.append('name', newUser.name);
    newForm.append('surname1', newUser.surname1);
    newForm.append('surname2', newUser.surname2);
    newForm.append('email', newUser.email);
    newForm.append('username', newUser.username);
    newForm.append('password', newUser.password);
    newForm.append('isArtist', '' + newUser.isArtist);
    newForm.append('imgAvatar', 'defaultAvatarImg.png');
    newForm.append('aboutMe', 'Bienvenido a mi perfil!!!');
    newForm.append('isPrivate', '' + newUser.isPrivate);

    return this.http.post(this.url + '/register', newForm);
  }

  edit(
    id: number,
    name: string,
    surname1: string,
    surname2: string,
    email: string,
    username: string,
    aboutMe: string,
    files: FileList
  ): Observable<any> {
    let newForm: FormData = new FormData();
    newForm.append('id', '' + id);
    newForm.append('name', name);
    newForm.append('surname1', surname1);
    newForm.append('surname2', surname2);
    newForm.append('email', email);
    newForm.append('username', username);
    newForm.append('aboutMe', aboutMe);
    if (files !== null) newForm.append('imgAvatar', files[0], files[0].name);
    //    TODO Cambiar a patch
    return this.http.post(this.url + '/user/' + id + '/profile', newForm);
  }

  existUserByEmail(id: number, email: string): Observable<any> {
    let newForm: FormData = new FormData();
    newForm.append('email', email);
    return this.http.post(this.url + '/user/' + id + '/existByEmail', newForm);
  }

  existUserByUsername(id: number, username: string): Observable<any> {
    let newForm: FormData = new FormData();
    newForm.append('username', username);
    return this.http.post(this.url + '/user/' + id + '/existByUsername', newForm);
  }

  changePassword(id: number, formValues): Observable<any> {
    let newForm: FormData = new FormData();
    newForm.append('id', '' + id);
    newForm.append('password', formValues.password);
    //    TODO Cambiar a patch
    return this.http.post(this.url + '/user/' + id + '/password', newForm);
  }

  getFollowSuggestions(id: number): Observable<any> {
    return this.http.get(this.url + '/user/' + id + '/followSuggestions');
  }

  getUserById(id: number): Observable<any> {
    return this.http.get(this.url + '/user/' + id);
  }

  isFollowingThatUser(id_follower: number, id_followed: number): Observable<any> {
    return this.http.get(this.url + '/user/' + id_follower + '/follow/' + id_followed);
  }

  requestOrFollowUser(
    id_follow: number,
    id_follower: number,
    id_followed: number,
    status_follow: number
  ): Observable<any> {
    let newForm: FormData = new FormData();
    if (id_follow != null) newForm.append('id_follow', '' + id_follow);
    newForm.append('status_follow', '' + status_follow);
    return this.http.post(this.url + '/user/' + id_follower + '/follow/' + id_followed, newForm);
  }

  updateFollowRequest(
    id_follow: number,
    id_follower: number,
    id_followed: number,
    status_follow: number
  ): Observable<any> {
    let newForm: FormData = new FormData();
    newForm.append('status_follow', '' + status_follow);
    //TODO cambiar a patch
    return this.http.post(this.url + '/user/' + id_follower + '/follow/' + id_followed + '/' + id_follow, newForm);
  }

  getFollowers(id: number): Observable<any> {
    return this.http.get(this.url + '/user/' + id + '/follower');
  }

  getFollowed(id: number): Observable<any> {
    return this.http.get(this.url + '/user/' + id + '/followed');
  }

  privateAccountSwitcher(user: User): Observable<any> {
    let newForm: FormData = new FormData();
    newForm.append('isPrivate', '' + user.isPrivate);
    // TODO: pasar a patch
    return this.http.post(this.url + '/user/' + user.id + '/settings/account/privacy',  newForm);
  }

  deactivateAccount(user: User): Observable<any> {
    let newForm: FormData = new FormData();
    newForm.append('id', '' + user.id);
    // TODO: pasar a patch
    return this.http.post(this.url + '/user/' + user.id + '/settings/account', newForm);
  }

  searchUser(searchedPattern: string): Observable<any> {
    let newForm: FormData = new FormData();
    newForm.append('searchedPattern', searchedPattern);
    return this.http.post(this.url + '/user/search', newForm);
  }

  sendContactForm(formValues:any): Observable<any> {
    let newForm: FormData = new FormData();
    newForm.append('name', formValues.name);
    newForm.append('surname1', formValues.surname1);
    newForm.append('email', formValues.email);
    newForm.append('phone', formValues.phone);
    newForm.append('bodyMessage', formValues.bodyMessage);
    return this.http.post(this.url + '/contact', newForm);
  }
}
