import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { Publication } from 'src/app/core/models/publication';
import { PublicationService } from 'src/app/core/services/publication.service';
import { SessionService } from 'src/app/core/services/session.service';
import { Location } from '@angular/common';

@Component({
  selector: 'app-edit-publication',
  templateUrl: './edit-publication.component.html',
  styleUrls: ['./edit-publication.component.css']
})
export class EditPublicationComponent implements OnInit {

  constructor(
    private _publicationService: PublicationService,
    private _sessionService: SessionService,
    private _router: ActivatedRoute,
    private _activeRoute: ActivatedRoute,
    private _location: Location
  ) { }

  user = this._sessionService.getCurrentUser();
  token = this._sessionService.getCurrentToken();

  id:number;
  id_user:number;
  imgToUpload: FileList = null;
  addImgPublication(imgPublication: FileList) {
      this.imgToUpload = imgPublication;
  }
  bodyPublication:string = "";
  n_likes:number;
  n_comments:number;


  idPublication:string = "";
  ngOnInit(): void {
    this._activeRoute.paramMap.subscribe(
      (params) => {
        this.idPublication = params.get('id');
        this._publicationService.getPublicationById(parseInt(this.idPublication), this.user.username, this.token).subscribe(
          (result) => {
            let miPublication = new Publication(
              result.id,
              result.id_user,
              result.imgToUpload,
              result.bodyPublication,
              result.upload_date,
              result.n_likes,
              result.n_comments
            );
            this.id = miPublication.id;
            this.id_user = miPublication.id_user;
            this.bodyPublication = miPublication.bodyPublication;
            this.n_likes = miPublication.n_likes;
            this.n_comments = miPublication.n_comments;
            // todo: si this.imgToUpload is null, no borro las imgs de la db, en caso contrario sí
          }, (error) => {
            console.log(error);
          }
        )
      }, (error) => {
        console.log(error);
      }
    )
  }

  editPublication() {
    this._publicationService.edit(
    new Publication(this.id, this.user.id, this.imgToUpload, this.bodyPublication, null, this.n_likes, this.n_comments),
    this.token).subscribe(
      (result) => {
        this._location.back();
      }, (error) => {
        console.log(error);
      }
    )
  }
}
