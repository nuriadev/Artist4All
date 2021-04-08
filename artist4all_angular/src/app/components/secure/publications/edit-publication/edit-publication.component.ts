import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
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

  idPublication:string = "";
  ngOnInit(): void {
    this._activeRoute.paramMap.subscribe(
      (params) => {
        this.idPublication = params.get('id');
        //    TODO: Llamar a publication service -->getPublicationById
      }, (error) => {
        console.log(error);
      }
    )
  }

  imgToUpload: FileList = null;
  addImgPublication(imgPublication: FileList) {
      this.imgToUpload = imgPublication;
  }

  bodyPublication:string = "";

  editPublication() {

  }
}
