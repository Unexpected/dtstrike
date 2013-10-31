package six.challenge.localtester;

import java.awt.BorderLayout;
import java.awt.FlowLayout;

import javax.swing.JButton;
import javax.swing.JDialog;
import javax.swing.JPanel;
import javax.swing.border.EmptyBorder;
import javax.swing.JLabel;

import net.miginfocom.swing.MigLayout;

import javax.swing.JSpinner;
import javax.swing.JSlider;
import javax.swing.JTextField;

import java.awt.event.ActionListener;
import java.awt.event.ActionEvent;
import java.util.ArrayList;

import com.cgi.itwar.map.*;

import javax.swing.JTextPane;

import java.io.File;
import java.io.FileWriter;
import java.awt.event.InputMethodListener;
import java.awt.event.InputMethodEvent;
import javax.swing.JScrollBar;
import javax.swing.JTextArea;
import javax.swing.JScrollPane;

public class MapGenerator extends JDialog {

	private final JPanel contentPanel = new JPanel();
	private JTextField textFieldEco;
	private JTextField textFieldMili;
	private JTextField textFieldNeutralEco;
	private JTextField textFieldNutralMili;
	private JTextArea textPane;
	private JTextField txtMymaptxt;
	private JTextField mapFieldFile;
	private JTextField labelPlayer;

	/**
	 * Launch the application.
	 */
	public static void main(JTextField mapField) {
		try {
			MapGenerator dialog = new MapGenerator(mapField);
			dialog.setDefaultCloseOperation(JDialog.DISPOSE_ON_CLOSE);
			dialog.setVisible(true);
		} catch (Exception e) {
			e.printStackTrace();
		}
	}

	/**
	 * Create the dialog.
	 */
	public MapGenerator(JTextField mapField) {
		setTitle("Mapgenerator");
		setDefaultCloseOperation(JDialog.DISPOSE_ON_CLOSE);
		

		this.mapFieldFile = mapField;
		setBounds(100, 100, 650, 480);
		getContentPane().setLayout(new BorderLayout());
		contentPanel.setBorder(new EmptyBorder(5, 5, 5, 5));
		getContentPane().add(contentPanel, BorderLayout.CENTER);
		contentPanel.setLayout(new MigLayout("", "[grow][grow]", "[][][][][][][][grow]"));
		{
			JLabel lblNumberOfPlayer = new JLabel("Number of Player (1-4)");
			contentPanel.add(lblNumberOfPlayer, "cell 0 0,alignx right");
		}
		{
			JLabel lblNumberOfMilitary = new JLabel("Number of Start Military Planets for each player (1-2)");
			contentPanel.add(lblNumberOfMilitary, "cell 0 1,alignx right");
		}
		{
			textFieldMili = new JTextField();
			textFieldMili.setColumns(10);
			contentPanel.add(textFieldMili, "cell 1 1,growx");
		}
		{
			JLabel lblNumberOfStart = new JLabel("Number of Start Economic Planets for each player (1-2)");
			contentPanel.add(lblNumberOfStart, "cell 0 2,alignx right");
		}
		{
			textFieldEco = new JTextField();
			contentPanel.add(textFieldEco, "cell 1 2,growx");
			textFieldEco.setColumns(10);
		}
		{
			JLabel lblNumberOfPlanets = new JLabel("Number of neutral Military Planets (0-3)");
			contentPanel.add(lblNumberOfPlanets, "cell 0 3,alignx right");
		}
		{
			textFieldNutralMili = new JTextField();
			textFieldNutralMili.setColumns(10);
			contentPanel.add(textFieldNutralMili, "cell 1 3,growx");
		}
		{
			JLabel lblNumberOfNeutral = new JLabel("Number of neutral Economic Planets (0-3)");
			contentPanel.add(lblNumberOfNeutral, "cell 0 4,alignx right");
		}
		{
			textFieldNeutralEco = new JTextField();
			textFieldNeutralEco.setColumns(10);
			contentPanel.add(textFieldNeutralEco, "cell 1 4,growx");
		}
		{
			JButton btnGenerate = new JButton("Generate");
			contentPanel.add(btnGenerate, "cell 1 5");
			btnGenerate.addActionListener(new ActionListener() {
				public void actionPerformed(ActionEvent e) {

					//(boolean debug, int pNbGamers, int pBasesPerGamer, int pColoniesPerGamer, int pneutralMilitary,	int pneutralEconomic)
					com.cgi.itwar.map.MapGenerator gene = new com.cgi.itwar.map.MapGenerator(true,Integer.parseInt(labelPlayer.getText()),Integer.parseInt(textFieldMili.getText()), Integer.parseInt(textFieldEco.getText()),Integer.parseInt(textFieldNutralMili.getText()),Integer.parseInt(textFieldNeutralEco.getText()));
					
					ArrayList<Colony> colonies = gene.getColonies();
					StringBuffer MapFileBuffer = new StringBuffer();
					for (Colony colony : colonies) {
						MapFileBuffer.append(colony.toMap());
						MapFileBuffer.append("\n");
					}
					String MapFile = MapFileBuffer.toString();
					textPane.setText(MapFile);
				}
			});
			btnGenerate.setActionCommand("OK");
		}
		{
			JLabel lblFilename = new JLabel("FileName");
			contentPanel.add(lblFilename, "cell 0 6,alignx trailing");
		}
		{
			txtMymaptxt = new JTextField();
			txtMymaptxt.setText("mymap.txt");
			txtMymaptxt.setColumns(10);
			contentPanel.add(txtMymaptxt, "cell 1 6,growx");
		}
		{
			JScrollPane scrollPane = new JScrollPane();
			contentPanel.add(scrollPane, "cell 0 7 2 1,grow");
			{
				textPane = new JTextArea();
				scrollPane.setViewportView(textPane);
			
			}
		}
		{
			labelPlayer = new JTextField();
			labelPlayer.setColumns(10);
			contentPanel.add(labelPlayer, "cell 1 0");
		}

		{
			JPanel buttonPane = new JPanel();
			buttonPane.setLayout(new FlowLayout(FlowLayout.RIGHT));
			getContentPane().add(buttonPane, BorderLayout.SOUTH);
			{
				JButton okButton = new JButton("OK");
				okButton.addActionListener(new ActionListener() {
					public void actionPerformed(ActionEvent e) {
						try {
							File save = new File("maps/"+txtMymaptxt.getText());
							save.createNewFile();
							FileWriter mapwriter = new FileWriter(save);
							mapwriter.write(textPane.getText());
							mapwriter.flush();
							mapwriter.close();
							
							mapFieldFile.setText("maps/"+txtMymaptxt.getText());
							
							dispose();
						} catch (Exception e1) {
							// TODO Auto-generated catch block
							e1.printStackTrace();
						}
					}
				});
				okButton.setActionCommand("OK");
				buttonPane.add(okButton);
				getRootPane().setDefaultButton(okButton);
			}
			{
				JButton cancelButton = new JButton("Cancel");
				cancelButton.addActionListener(new ActionListener() {
					public void actionPerformed(ActionEvent arg0) {
						dispose();
					}
				});
				cancelButton.setActionCommand("Cancel");
				buttonPane.add(cancelButton);
			}
		}
	}

}
