import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { Publication } from '../models/publication';

@Injectable({
  providedIn: 'root',
})
export class PublicationService {
  constructor(private http: HttpClient) {}

  private url = 'http://localhost:81/user/';

  create(
    id_user: number,
    newPublication: Publication,
    token: string
  ): Observable<any> {
    let newForm: FormData = new FormData();
    newForm.append('id_user', '' + newPublication.id_user);
    if (!newPublication.imgsPublication) {
      newForm.append('imgsPublication', null);
    } else {
      let imgsPublication = [];
      for (var i = 0; i < newPublication.imgsPublication.length; i++) {
        imgsPublication[i] = newPublication.imgsPublication[i].name;
      }
      newForm.append('imgsPublication', JSON.stringify(imgsPublication));
    }
    newForm.append('bodyPublication', newPublication.bodyPublication);
    newForm.append('upload_date', '' + newPublication.upload_date);
    newForm.append('n_likes', '' + newPublication.n_likes);
    newForm.append('n_comments', '' + newPublication.n_comments);
    newForm.append('token', token);

    return this.http.post(this.url + id_user + '/publication', newForm);
  }

  getUserPublications(id_user: number, token: string): Observable<any> {
    return this.http.get(this.url + id_user + '/publication', {
      headers: new HttpHeaders({ Authorization: token }),
    });
  }

  getPublicationById(
    id_user: number,
    id_publication: number,
    token: string
  ): Observable<any> {
    return this.http.get(
      this.url + id_user + '/publication/' + id_publication,
      {
        headers: new HttpHeaders({ Authorization: token }),
      }
    );
  }

  edit(publication: Publication, token: string): Observable<any> {
    let newForm: FormData = new FormData();
    if (!publication.imgsPublication) {
      newForm.append('imgsPublication', null);
    } else {
      let imgsPublication = [];
      for (var i = 0; i < publication.imgsPublication.length; i++) {
        imgsPublication[i] = publication.imgsPublication[i].name;
      }
      newForm.append('imgsPublication', JSON.stringify(imgsPublication));
    }
    newForm.append('bodyPublication', publication.bodyPublication);
    newForm.append('token', token);

    // TODO: pasar a patch
    return this.http.post(
      this.url + publication.id_user + '/publication/' + publication.id,
      newForm
    );
  }

  delete(
    id_user: number,
    id_publication: number,
    token: string
  ): Observable<any> {
    return this.http.delete(
      this.url + id_user + '/publication/' + id_publication,
      {
        headers: new HttpHeaders({ Authorization: token }),
      }
    );
  }

  addLike(
    publication: Publication,
    my_id: number,
    id_publisher: number,
    token: string
  ): Observable<any> {
    let newForm: FormData = new FormData();
    newForm.append('id_publisher', '' + id_publisher);
    return this.http.post(
      this.url + my_id + '/like/publication' + publication.id,
      newForm,
      { headers: new HttpHeaders({ Authorization: token }) }
    );
  }

  removelike(
    publication: Publication,
    my_id: number,
    token: string
  ): Observable<any> {
    let options = {
      headers: new HttpHeaders({
        'Content-Type': 'application/json',
        Authorization: token,
      }),
      body: { publication: publication.id_user },
    };
    return this.http.delete(
      this.url + my_id + '/like/publication' + publication.id,
      options
    );
  }
}
