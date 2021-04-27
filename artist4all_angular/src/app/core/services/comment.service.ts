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
    newForm.append('id', '' + comment.id);
    newForm.append('id_user', '' + comment.user.id);
    newForm.append('bodyComment', comment.bodyComment);
    newForm.append('isEdited', '' + comment.isEdited);
    newForm.append('comment_date', null);
    newForm.append('id_comment_reference', '' + comment.id_comment_reference);
    if (comment.user_reference != null) {
      newForm.append('id_user_reference', '' + comment.user_reference.id);
    }
    return this.http.post(this.url + comment.user.id + '/publication/' + comment.id_publication + '/comment', newForm);
  }

  getPublicationComments(id_user: number, id_publication: number): Observable<any> {
    return this.http.get(this.url + id_user + '/publication/' + id_publication + '/comment');
  }

  getCommentSubcomments(id_user: number, id_publication: number, id_comment: number): Observable<any> {
    return this.http.get(this.url + id_user + '/publication/' + id_publication + '/comment/' + id_comment);
  }

  editComment(comment: Comment): Observable<any> {
    let newForm: FormData = new FormData();
    newForm.append('bodyComment', comment.bodyComment);
    newForm.append('isEdited', '' + comment.isEdited);
    return this.http.post(this.url + comment.user.id + '/publication/' + comment.id_publication + '/comment/' + comment.id, newForm);
  }

  delete(id_user: number, id_publication: number, comment: Comment): Observable<any> {
    return this.http.delete(this.url + id_user + '/publication/' + id_publication + '/comment/' + comment.id);
  }

}
