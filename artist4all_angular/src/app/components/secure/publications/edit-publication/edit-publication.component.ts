import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { Publication } from 'src/app/core/models/publication';
import { PublicationService } from 'src/app/core/services/publication.service';
import { SessionService } from 'src/app/core/services/session.service';

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
  ) { }

  user = this._sessionService.getCurrentUser();
  token = this._sessionService.getCurrentToken();

  bodyPublication:string = "";

  imgToUpload: FileList = null;
  addImgPublication(imgPublication: FileList) {
      this.imgToUpload = imgPublication;
  }

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
            this.bodyPublication = miPublication.bodyPublication;
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

  }
}
