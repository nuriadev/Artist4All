import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { Comment } from '../models/comment';

@Injectable({
  providedIn: 'root',
})
export class CommentService {
  constructor(private http: HttpClient) {}

  private url = 'http://localhost:81/user/';

  postComment(comment: Comment): Observable<any> {
    let newForm: FormData = new FormData();

    return this.http.post(this.url, newForm);
  }

  getCommentSubcomments(id_user: number): Observable<any> {
    return this.http.get(this.url + id_user + '/publication');
  }

  getCommentById(id_user: number, id_publication: number): Observable<any> {
    return this.http.get(this.url + id_user + '/publication/' + id_publication);
  }

  edit(comment: Comment): Observable<any> {
    let newForm: FormData = new FormData();
    return this.http.post(this.url, newForm);
  }

  delete(id_user: number, id_publication: number): Observable<any> {
    return this.http.delete(this.url + id_user + '/publication/' + id_publication);
  }

}
